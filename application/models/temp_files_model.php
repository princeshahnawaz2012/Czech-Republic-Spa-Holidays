<?php

/**
 * Description of temp_files_model
 * @author Sergii Lapin
 */
class Temp_files_model extends CI_Model
{
	private $table = 'temp_files';
	private $table_fields = array('id', 'created_at');
	
	/**
	 * Создает запись в базе о временном файле
	 * 
	 * @return int идентификатор файла
	 */
	public function get()
	{
		$this->clear_trush();
		$this->db->set('created_at', time())->insert($this->table);
		return $this->db->insert_id();
	}
	
	/**
	 * Удаляет временные файлы и инфо о них из базы
	 * 
	 * @return int количество удаленных строк в базе
	 */
	private function clear_trush()
	{
		$nTimeLimit = time() - $this->config->item('temp_files_expire');
		$aIds = $this->db->select('id')->where('created_at < ',$nTimeLimit)->get($this->table)->result_array();
		foreach ($aIds as $aId)
		{
			$sMask = './' . $this->config->item('temp_files_dir') . $aId['id'] . '.*';
			foreach(glob($sMask) as $sFileName)
			{
				unlink($sFileName);
			}
		}
		$this->db->where('created_at < ',$nTimeLimit);
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}
	
	/**
	 * Удаляет временный файл по ID с папки и базы
	 * @param int $nTempFileId идентификатор временного файла
	 * @return int количество удаленных строк из базы
	 */
	public function delete($nTempFileId = 0)
	{
		if ( ! $nTempFileId )
		{
			return 0;
		}
		$sMask = './' . $this->config->item('temp_files_dir') . $nTempFileId . '.*';
		foreach(glob($sMask) as $sFileName)
		{
			unlink($sFileName);
		}
		$this->db->where('id', $nTempFileId)->delete($this->table);
		return $this->db->affected_rows();
	}
	
	/**
	 * Удаляет старый файл из папки хранения и переносит туда новый
	 * Если нового файла нет, то просто удаляет старый
	 * 
	 * @param int $nTempFileId
	 * @param string $sFileExt
	 * @param string $sDestDir
	 * @param string $sNewName
	 * @return string расширения нового файла 
	 */
	public function move($nTempFileId = 0, $sFileExt = '', $sDestDir = '', $sNewName = '')
	{
		//если не укзана папка назначения или название нового файла
		if ( ! $sDestDir || ! $sNewName )
		{
			return '';
		}
		
		//удаляем старый файлы в папке назначения
		$sDestMask = $sDestDir . $sNewName . '.*';
		foreach (glob($sDestMask) as $sFileName)
		{
			unlink($sFileName);
		}
		
		//если нет название или разширения исходного файла, то выходим из функции
		if ( ! $nTempFileId || ! $sFileExt )
		{
			return '';
		}
		
		$sSourcePath = './' . $this->config->item('temp_files_dir') . $nTempFileId . '.' . $sFileExt;
		$sDestPath = $sDestDir . $sNewName . '.' . $sFileExt;
		
		
		//копируем новый файл в папку назначения
		if ( ! copy($sSourcePath, $sDestPath) )
		{
			return '';
		}
		$this->db->where('id', $nTempFileId)->delete($this->table);
		@unlink($sSourcePath);
		return $sFileExt;
	}
}

/* End of file temp_files_model.php */
/* Location: ./application/models/temp_files_model.php */