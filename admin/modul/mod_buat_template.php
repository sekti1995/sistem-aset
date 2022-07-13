<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<script type="text/javascript">
$(document).ready(function(){
$(function() {
		function GetURLParameter(sParam)
		{
			var sPageURL = window.location.search.substring(1);
			var sURLVariables = sPageURL.split('&');
			for (var i = 0; i < sURLVariables.length; i++) 
			{
				var sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] == sParam) 
				{
					return sParameterName[1];
				}
			}
		}	
    var msg = GetURLParameter('msg');	
	if(msg){
		var msg = msg.replace(/\%20/g, " ");
		$('#msg').html(msg);	
		$( "#msg" ).dialog({
			autoOpen: true,
			height: 200,
			width: 300,
			modal: true,
			text : "tes",
			buttons: {
				"Ok": function() {
				$( this ).dialog( "close" );
				}
			},
			close: function() {
				window.location('location:media.php?module=pendaftaran&form_skpd');
			}
		});
	}
	});
jQuery.fn.extend({
insertAtCaret: function(myValue){
  return this.each(function(i) {
    if (document.selection) {
      //For browsers like Internet Explorer
      this.focus();
      sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      //For browsers like Firefox and Webkit based
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  })
}
});
	$('.kamus').click(function() {
	//var active = $( "#tabs" ).tabs( "option", "active" );
	//if(active==1) var v = 'sk'; else var v ='ba';
	   tinymce.get('ba').execCommand('insertHTML', false, $(this).val());
	});	

});
</script>
<script>
   tinymce.init({selector: "textarea",width:"850px",height:"270px", theme: "modern",
					plugins: [
						 "charmap","advlist autolink link image lists charmap print preview hr anchor pagebreak",
						 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
						 "table contextmenu directionality emoticons paste textcolor filemanager code "
				   ],
				   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
				   toolbar2: "| filemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
				   image_advtab: true 
				});
</script>
</head>
<body>
<form action="./aksi.php?module=buat_template" method="POST" name="Form1" id="signupForm">
<div id="tabs-1">
<?php
//$data = ambilFieldData("template",'template','nama_template','ba');
?>
<textarea name="ba"></textarea>
</div>
<div style="top:160px;left:940px;position:absolute;">
Kamus :<br/>
<button type="button" class="kamus" value="{no_kepanitiaan}">No. Kepanitiaan</button>
<button type="button" class="kamus" value="{tgl_kepanitiaan}">Tgl. Kepanitiaan</button>
<br/>
<button type="button" class="kamus" value="{paket}">Paket</button>
<button type="button" class="kamus" value="{skpd}">SKPD</button>
<button type="button" class="kamus" value="{instansi}">Instansi</button>
<button type="button" class="kamus" value="{pepe}">Jabatan</button>
<br/>
<button type="button" class="kamus" value="{ta}">Tahun Anggaran</button>
<button type="button" class="kamus" value="{sumber}">Sumber</button>
<button type="button" class="kamus" value="{pagu}">Pagu</button>
<br/>
<button type="button" class="kamus" value="{terbilang_pagu}">Terbilang Pagu</button>
<button type="button" class="kamus" value="{dpa}">DPA</button>
<button type="button" class="kamus" value="{tgl_dpa}">Tgl. DPA</button>
<br/>
<button type="button" class="kamus" value="{tgl_ba}">Tanggal BA</button>
<button type="button" class="kamus" value="{bulan_ba}">Bulan BA</button>
<button type="button" class="kamus" value="{tahun_ba}">Tahun BA</button>
<br/>
<button type="button" class="kamus" value="{tgl_ba_ang}">Tanggal BA Angka</button>
<button type="button" class="kamus" value="{hari_ba}">Hari BA</button>
<br/>
<button type="button" class="kamus" value="{ketua}">Ketua</button>
<button type="button" class="kamus" value="{nipket}">NIP Ketua</button>
<button type="button" class="kamus" value="{golongan_ketua}">Gol. Ketua</button>
<br/>
<button type="button" class="kamus" value="{sekretaris}">Sekretaris</button>
<button type="button" class="kamus" value="{nipsek}">NIP Sekret</button>
<button type="button" class="kamus" value="{golongan_sek}">Gol. Sekret</button>
<br/>
<button type="button" class="kamus" value="{anggota}">Anggota</button>
<button type="button" class="kamus" value="{nipang}">NIP Anggota</button>
<button type="button" class="kamus" value="{golongan_ang}">Gol. Anggota</button>
<br/>
<button type="button" class="kamus" value="{ppkom}">PPKom</button>
<button type="button" class="kamus" value="{nipppkom}">NIP PPKom</button>
<button type="button" class="kamus" value="{golongan_ppkom}">Gol. PPKom</button>
<br/>
<button type="button" class="kamus" value="{pp}">Pej. Peng.</button>
<button type="button" class="kamus" value="{nippp}">NIP Pej. Peng.</button><br/>
<button type="button" class="kamus" value="{golongan_pp}">Gol. Pej. Peng.</button>
<br/>
</div><br/>
<button type="submit" class="button" accesskey="S" name="oper" value="add"><u>S</u>impan</button>
</form>
</body>
</html>