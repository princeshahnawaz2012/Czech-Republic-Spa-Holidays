<?php

function smarty_modifier_my_sum_format($sum)
{
	if ($sum > 1000000)
	{
		return ( round( $sum/1000000, 2)).' million';
	} else
	{
		return ((int)$sum/1000).' thousand';
	}
} 

?>