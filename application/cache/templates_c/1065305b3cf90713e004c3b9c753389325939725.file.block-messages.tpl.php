<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 12:40:05
         compiled from "application//views/regions/block-messages.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18242638955039eef5824fe3-15750838%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1065305b3cf90713e004c3b9c753389325939725' => 
    array (
      0 => 'application//views/regions/block-messages.tpl',
      1 => 1341115082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18242638955039eef5824fe3-15750838',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aErrors' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039eef58be7c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039eef58be7c')) {function content_5039eef58be7c($_smarty_tpl) {?><script type="text/javascript">
errors = <?php if (isset($_smarty_tpl->tpl_vars['aErrors']->value)){?><?php echo json_encode($_smarty_tpl->tpl_vars['aErrors']->value);?>
<?php }else{ ?>[]<?php }?>;
var general_errors = [];
var messages = [];
function show_messages()
{
        for(var i=0; i<general_errors.length; i++)
        {
                $("#error").html($("#error").html()+general_errors[i]);
        }
        for(i=0; i<messages.length; i++)
        {
                $("#state").html($("#state").html()+messages[i]);
        }
        $("#OneErrors").show();
        setTimeout(function(){
                $("#OneErrors").hide();
        },4000);
}
$(document).ready(function()
{
        for(var i in errors)
        {
                if(errors[i].type=='error')
                {
                        if(errors[i].field)
                        {
								if(errors[i].message != '')
								{
									input = $("[name="+errors[i].field+"]");
									input.addClass('error_input');
									$('<div class="error">'+errors[i].message+'</div>').insertAfter(input);
								}
                        }
                        else
                        {
                                general_errors[general_errors.length] = '<p>'+errors[i].message+'</p>';
                        }
                }
                else
                {
                        messages[messages.length] = '<p>'+errors[i].message+'</p>';
                }
        }
        show_messages();
});
</script>


<div id = "OneErrors" style="display:none;" align="center">

	<div class="msg" id="msg">
	</div>

	<div class="state-holder" style="margin-left:auto; margin-right:auto;">
		<div id="state" class="saved-state" >
		</div>

		<div id="error" class="error-state" >
		</div>
	</div>
</div>
<?php }} ?>