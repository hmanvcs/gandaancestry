<?php
	require_once APPLICATION_PATH.'/includes/header.php';
	
	$clan = new Clan(); 
	$clanid = $request->clanid;
	if(!isEmptyString($clanid)){
		$clan->populate(decode($clanid));
		
	}
	$type = $request->type;
	$clan->setType($type);
	
	$posturl = $this->baseUrl("clan/processcreate");
	$button_title = $this->translate("clan_button_newname"); 
	// debugMessage($clan->toArray());
	
	#in case of errors in session, populate the fields from session
	if(!isEmptyString($session->getVar(ERROR_MESSAGE))){ 
		$clan->processPost($session->getVar(FORM_VALUES));	
	}
	
	$title = "New Clan";
	$this->headTitle($title);
	
?>
<div class="popupdiv">
<form class="form-horizontal well span6" id="clanform" action="<?php echo $posturl; ?>" method="post">
    <table class="table">
		<?php if($sessionhaserror) { ?>
	        <tr>
	        	<td colspan="2"><div class="alert alert-error"><?php echo $session->getVar(ERROR_MESSAGE); ?></div></td>
	        </tr>
        <?php } ?>
        <?php if($clan->getType() == 1){ ?>
        <tr>
            <td><label class="control-label"><?php echo $this->translate("clan_ekika_label")." (".$this->translate("clan_fullname_label").")"; ?>:</label></td>
            <td><input name="fullname" id="fullname" type="text" class="span2 hastooltip" value="" title="<?php echo $this->translate("clan_lugname_tooltip"); ?>" /><div id="fullname_error"></div></td>
        </tr>
        <tr>
            <td><label class="control-label"><?php echo $this->translate("clan_omuziro_label")." (".$this->translate("clan_totem_label").")"; ?>:</label></td>
            <td><input name="omuziro" id="omuziro" type="text" class="span2 hastooltip" value="" title="<?php echo $this->translate("clan_omuziro_tooltip"); ?>" /></td>
        </tr>
        <tr>
            <td><label class="control-label"><?php echo $this->translate("clan_akabbiro_label"); ?></td>
            <td><input name="akabbiro" id="akabbiro" type="text" class="span4 hastooltip" value="" title="<?php echo $this->translate("clan_akabbiro_tooltip"); ?>" /></td>
        </tr>
        <?php } ?>
        <?php if($clan->getType() == 2){ ?>
        <tr>
            <td><label class="control-label">Clan:</label></td>
            <td><?php echo $clan->getFullName(); ?><input type="hidden" id="clanid" name="clanid" value="<?php echo decode($clanid); ?>" /></td>
        </tr>
        <tr>
            <td><label class="control-label">Ssiga Name:</label></td>
            <td><input name="fullname" id="fullname" type="text" class="span3 hastooltip" value="" title="<?php echo $this->translate("clan_lugname_tooltip"); ?>" /><div id="fullname_error"></div></td>
        </tr>
        <?php } ?>        
        <tr class="hidden">
            <td></td>
            <td class="nowrapping" colspan="3">
            <input type="hidden" name="entityname" value="Clan" />
			    <input type="hidden" id="id" name="id" value="" />
			    <input type="hidden" id="type" name="type" value="<?php echo $request->type; ?>" />
			    <input type="hidden" name="<?php echo URL_FAILURE; ?>" value="<?php echo encode($this->baseUrl("clan/add/pgc/true/type/".$type)); ?>" />			   				
                <input type="hidden" name="<?php echo URL_SUCCESS; ?>" value="<?php echo encode($this->baseUrl('clan/addsuccess')); ?>" />
            </td>
        </tr>
  </table>
</form>
</div>
<?php
	require_once APPLICATION_PATH.'/includes/footer.php';
?>
<script>
	$(document).ready(function() {
		$("#clanform").validate({		
			// define the validation rules one field at a time
			rules: {
				fullname: "required"
			}, 
			// the messages for each of the fields being validated
			messages: {		
				fullname: "<?php echo $this->translate("clan_fullname_error"); ?>"
			},
			// custom error positions
			errorPlacement: function(error, element) {
				switch(element.attr("name")){					
					default:
						error.appendTo("#"+element.attr("name")+"_error")
						break;
				}			
			}
		});
		
		// tooltips in popup
		$(".hastooltip").each(function(){		
			var theid = $(this).attr('id');
			var thepath = '<?php echo $this->baseUrl('images/questionmark.png'); ?>';
			if($(this).attr('title') != "undefined" || $(this).attr('title') != ""){
				$('<a href="javascript: void(0);" class="qcontainer" id="q_'+theid+'"><img src="'+thepath+'" /></a>').insertAfter(this);
				$("#q_"+theid).attr('title', $(this).attr('title')).addClass('qtooltip');
			}	    
		}); 
		$('.qtooltip').tipsy({fade: true, gravity: 'w', html: true, trigger: 'hover', offset: -5});
    	$(".hastooltip").attr('title','');
		
		// reset tab index
		var tabindex = 1;
		$('input,select').each(function() {
			if (this.type != "hidden") {
				var $input = $(this);
				$input.attr("tabindex", tabindex);
				tabindex++;
			}
		});
		
	}); 
</script>
<style>
body {
	overflow:hidden;
}
</style>