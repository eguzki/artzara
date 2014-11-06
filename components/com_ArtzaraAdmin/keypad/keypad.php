<?php

	function showKeypad($formName= 'adminForm', $fieldName = 'displayOut',$passwd = 0){
		global $artzaraadminurl;
		$imagesPath = $artzaraadminurl."keypad/";
?>
	<script language="JavaScript1.2">

	<!--
	function FP_swapImg() {//v1.0
	 var doc=document,args=arguments,elm,n; doc.$imgSwaps=new Array(); for(n=2; n<args.length;
	 n+=2) { elm=FP_getObjectByID(args[n]); if(elm) { doc.$imgSwaps[doc.$imgSwaps.length]=elm;
	 elm.$src=elm.src; elm.src=args[n+1]; } }
	}

	function FP_getObjectByID(id,o) {//v1.0
	 var c,el,els,f,m,n; if(!o)o=document; if(o.getElementById) el=o.getElementById(id);
	 else if(o.layers) c=o.layers; else if(o.all) el=o.all[id]; if(el) return el;
	 if(o.id==id || o.name==id) return o; if(o.childNodes) c=o.childNodes; if(c)
	 for(n=0; n<c.length; n++) { el=FP_getObjectByID(id,c[n]); if(el) return el; }
	 f=o.forms; if(f) for(n=0; n<f.length; n++) { els=f[n].elements;
	 for(m=0; m<els.length; m++){ el=FP_getObjectByID(id,els[n]); if(el) return el; } }
	 return null;
	}
	-->

	function addChar(f, character)
	{
		// make sure input.value is a string
		var numOut;
		campo = eval('f.<?php echo $fieldName; ?>');
		if (f.led.value == null)
			f.led.value = character;
		else
			f.led.value += character;

		if(campo != null){
			numOut = parseFloat(f.led.value);
			if(!isNaN(numOut)){
				campo.focus();
				campo.value = f.led.value;
				campo.blur();
			}
		}
	}

	function clearDisplay(f)
	{
		campo = eval('f.<?php echo $fieldName; ?>');
		f.led.value = "";
		if(campo != null){
			campo.focus();
			campo.value = f.led.value;
			campo.blur();
		}

	}

	</script>
	
	<div align="center">
	<table border="1" width="160" bgcolor="#CCFFFF" class="keypad" id="table1">
		<tr>
			<td colspan="3">
				<input readonly <?php if($passwd)echo "type=\"password\""; ?> class="keypad" name="led"/>
			</td>
		</tr>
		<tr>
			<td>
			<img border="0" id="img21" src="<?php echo $imagesPath; ?>keypad7_2.jpg" height="40" width="50" alt="7" onClick="addChar(document.<?php echo $formName; ?>,'7')" onmouseover="FP_swapImg(1,0,'img21','<?php echo $imagesPath; ?>keypad7_0.jpg')" onmouseout="FP_swapImg(0,0,'img21','<?php echo $imagesPath; ?>keypad7_2.jpg')" onmousedown="FP_swapImg(1,0,'img21','<?php echo $imagesPath; ?>keypad7_1.jpg')" onmouseup="FP_swapImg(0,0,'img21','<?php echo $imagesPath; ?>keypad7_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="7"></td>
			<td>
			<img border="0" id="img22" src="<?php echo $imagesPath; ?>keypad8_2.jpg" height="40" width="50" alt="8" onClick="addChar(document.<?php echo $formName; ?>,'8')" onmouseover="FP_swapImg(1,0,/*id*/'img22',/*url*/'<?php echo $imagesPath; ?>keypad8_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img22',/*url*/'<?php echo $imagesPath; ?>keypad8_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img22',/*url*/'<?php echo $imagesPath; ?>keypad8_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img22',/*url*/'<?php echo $imagesPath; ?>keypad8_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="8"></td>
			<td>
			<img border="0" id="img23" src="<?php echo $imagesPath; ?>keypad9_2.jpg" height="40" width="50" alt="9" onClick="addChar(document.<?php echo $formName; ?>,'9')" onmouseover="FP_swapImg(1,0,/*id*/'img23',/*url*/'<?php echo $imagesPath; ?>keypad9_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img23',/*url*/'<?php echo $imagesPath; ?>keypad9_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img23',/*url*/'<?php echo $imagesPath; ?>keypad9_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img23',/*url*/'<?php echo $imagesPath; ?>keypad9_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="9"></td>
		</tr>
		<tr>
			<td>
			<img border="0" id="img18" src="<?php echo $imagesPath; ?>keypad4_2.jpg" height="40" width="50" alt="4" onClick="addChar(document.<?php echo $formName; ?>,'4')" onmouseover="FP_swapImg(1,0,/*id*/'img18',/*url*/'<?php echo $imagesPath; ?>keypad4_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img18',/*url*/'<?php echo $imagesPath; ?>keypad4_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img18',/*url*/'<?php echo $imagesPath; ?>keypad4_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img18',/*url*/'<?php echo $imagesPath; ?>keypad4_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="4"></td>
			<td>
			<img border="0" id="img19" src="<?php echo $imagesPath; ?>keypad5_2.jpg" height="40" width="50" alt="5" onClick="addChar(document.<?php echo $formName; ?>,'5')" onmouseover="FP_swapImg(1,0,/*id*/'img19',/*url*/'<?php echo $imagesPath; ?>keypad5_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img19',/*url*/'<?php echo $imagesPath; ?>keypad5_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img19',/*url*/'<?php echo $imagesPath; ?>keypad5_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img19',/*url*/'<?php echo $imagesPath; ?>keypad5_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="5"></td>
			<td>
			<img border="0" id="img20" src="<?php echo $imagesPath; ?>keypad6_2.jpg" height="40" width="50" alt="6" onClick="addChar(document.<?php echo $formName; ?>,'6')" onmouseover="FP_swapImg(1,0,/*id*/'img20',/*url*/'<?php echo $imagesPath; ?>keypad6_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img20',/*url*/'<?php echo $imagesPath; ?>keypad6_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img20',/*url*/'<?php echo $imagesPath; ?>keypad6_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img20',/*url*/'<?php echo $imagesPath; ?>keypad6_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="6"></td>
		</tr>
		<tr>
			<td>
			<img border="0" id="img15" src="<?php echo $imagesPath; ?>keypad1_2.jpg" height="40" width="50" alt="1" onClick="addChar(document.<?php echo $formName; ?>,'1')" onmouseover="FP_swapImg(1,0,/*id*/'img15',/*url*/'<?php echo $imagesPath; ?>keypad1_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img15',/*url*/'<?php echo $imagesPath; ?>keypad1_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img15',/*url*/'<?php echo $imagesPath; ?>keypad1_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img15',/*url*/'<?php echo $imagesPath; ?>keypad1_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="1"></td>
			<td>
			<img border="0" id="img16" src="<?php echo $imagesPath; ?>keypad2_2.jpg" height="40" width="50" alt="2" onClick="addChar(document.<?php echo $formName; ?>,'2')" onmouseover="FP_swapImg(1,0,/*id*/'img16',/*url*/'<?php echo $imagesPath; ?>keypad2_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img16',/*url*/'<?php echo $imagesPath; ?>keypad2_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img16',/*url*/'<?php echo $imagesPath; ?>keypad2_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img16',/*url*/'<?php echo $imagesPath; ?>keypad2_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="2"></td>
			<td>
			<img border="0" id="img17" src="<?php echo $imagesPath; ?>keypad3_2.jpg" height="40" width="50" alt="3" onClick="addChar(document.<?php echo $formName; ?>,'3')" onmouseover="FP_swapImg(1,0,/*id*/'img17',/*url*/'<?php echo $imagesPath; ?>keypad3_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img17',/*url*/'<?php echo $imagesPath; ?>keypad3_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img17',/*url*/'<?php echo $imagesPath; ?>keypad3_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img17',/*url*/'<?php echo $imagesPath; ?>keypad3_0.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="3"></td>
		</tr>
		<tr>
			<td>
			<img border="0" id="img12" src="<?php echo $imagesPath; ?>keypaddot_2.jpg" height="40" width="50" alt="." onClick="addChar(document.<?php echo $formName; ?>,'.')" onmouseover="FP_swapImg(1,0,/*id*/'img12',/*url*/'<?php echo $imagesPath; ?>keypaddot_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img12',/*url*/'<?php echo $imagesPath; ?>keypaddot_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img12',/*url*/'<?php echo $imagesPath; ?>keypaddot_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img12',/*url*/'<?php echo $imagesPath; ?>keypaddot_0.jpg')"></td>
			<td>
			<img border="0" id="img14" src="<?php echo $imagesPath; ?>keypad0_2.jpg" height="40" width="50" alt="0" onClick="addChar(document.<?php echo $formName; ?>,'0')" onmouseover="FP_swapImg(1,0,/*id*/'img14',/*url*/'<?php echo $imagesPath; ?>keypad0_0.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img14',/*url*/'<?php echo $imagesPath; ?>keypad0_2.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img14',/*url*/'<?php echo $imagesPath; ?>keypad0_1.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img14',/*url*/'<?php echo $imagesPath; ?>keypad0_0.jpg')"></td>
			<td>
			<img border="0" id="img13" src="<?php echo $imagesPath; ?>keypadCE3.jpg" height="40" width="50" alt="CE" onClick="clearDisplay(document.<?php echo $formName; ?>)" onmouseover="FP_swapImg(1,0,/*id*/'img13',/*url*/'<?php echo $imagesPath; ?>keypadCE.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img13',/*url*/'<?php echo $imagesPath; ?>keypadCE3.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img13',/*url*/'<?php echo $imagesPath; ?>keypadCE2.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img13',/*url*/'<?php echo $imagesPath; ?>keypadCE.jpg')" fp-style="fp-btn: Soft Tab 7; fp-proportional: 0" fp-title="CE"></td>
		</tr>
	</table>
	</div>
<?php
}
?>
