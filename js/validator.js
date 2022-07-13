function fn_SinyalHapus(idx, nama, module,halaman,total,rowsPerPage,kodeb)
	{
	input_box=confirm('Anda yakin akan menghapus ' + module + ' ' + nama + ' ?');
	if (input_box==true)
	{ 
	// kalo di klik ok
    window.location.href='./aksi.php?module=' + module + '&act=hapus&id=' + idx + '&kode=' + nama + '&page=' + halaman + '&totalDat=' + total +'&rowsPerPage=' + rowsPerPage +'&kodeb=' + kodeb;
	}
	
	else
	{
	// kalo di klik cancel
	 void('');
	}
	}

function multiTombol(urlnya)
{

	document.Form1.action = urlnya;
	document.Form1.target = "_self";    // Open in a same window
	document.Form1.submit();             // Submit the page
	return true;

}

function refreshParent() {
  window.opener.location.href = window.opener.location.href;

  if (window.opener.progressWindow)
		
 {
    window.opener.progressWindow.close()
  }
  window.close();
}

function validateForm()
{
	myOption = -1;
	for (i=myForm.keterangan.length-1; i > -1; i--) {
		if (myForm.keterangan[i].checked) {
		myOption = i; i = -1;
		}
	}
	if (myOption == -1) {
	alert("Pilih alasan tidak masuk !");
	return false;
	}
	// kalo gak kasi nama
	var x=document.forms["myForm"]["id_pegawai"].value;
	var y=document.forms["myForm"]["nama_tugas"].value;
	var z=document.forms["myForm"]["no_surat"].value;
	var a=document.forms["myForm"]["mulai_tugas"].value;
	var b=document.forms["myForm"]["selesai_tugas"].value;
	var sMsg = "";
		if(x == ""){
			sMsg += ("\n* Anda belum mengisikan nama");
		}
		if(y == ""){
			sMsg += ("\n* Anda belum mengisikan alasan");
		}
		if(z == ""){
			sMsg += ("\n* Anda belum mengisikan nomor surat alasan");
		}
		if(a == ""){
			sMsg += ("\n* Anda belum mengisikan tanggal mulai tidak masuk");
		}
		if(b == ""){
			sMsg += ("\n* Anda belum mengisikan tanggal selesai tidak masuk");
		}
		if(sMsg != ""){
			alert("Peringatan:\n" + sMsg);
			return false;
		}

}

function goBack()
  {
  window.history.go(-1)
  }

function validasiCombo(form){
	var cek = true;
	$.each($('#'+form+' .easyui-combobox'), function( index, value ) {
		if(value.disabled==false){
			var pilih = $('#'+value.id).combobox('getValue');
			var data = $('#'+value.id).combobox('getData');
			for(var i=0; i<data.length; i++){
				if (data[i].id == pilih){return true}
			}
			$('#'+value.id).combobox('showPanel');
			$.messager.alert('Peringatan','Data yang anda masukkan tidak ada dalam pilihan !');
			cek = false;
			return false;
		}
	});
	return cek;
}

function validasiCombo2(editor){
	var cek = true;
	$.each(editor, function( index, value ) {
		if(value.type=='combobox'){
			var pilih = $(value.target).combobox('getValue');
			var data = $(value.target).combobox('getData');
			for(var i=0; i<data.length; i++){
				if (data[i].id == pilih){return true}
			}
			$(value.target).combobox('showPanel');
			$.messager.alert('Peringatan','Data yang anda masukkan tidak ada dalam pilihan !');
			cek = false;
			return false;
		}
	});
	return cek;
}


$.extend($.fn.validatebox.defaults.rules, { 
	validDate: {  
		validator: function(value, param){  
			var date = myparser(value);
			var s = myformatter(date);
			return s==value; 
		},  
		message: 'Format tanggal tidak sesuai!'  
	}
}); 
