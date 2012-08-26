<script type="text/javascript">
errors = {if isset($aErrors)}{$aErrors|json_encode}{else}[]{/if};
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
