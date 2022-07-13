function fn_ValForm()
	{
		var input = document.getElementById('usr');
		var file = input.files[0];
		var te = document.getElementById("usr").value;
		var thisext = te.substr(te.lastIndexOf('.'));
		var sMsg = "";
		if(document.getElementById("kode").value == ""){
			sMsg += ("\n* Code cannot be empty");
		}
		if(document.getElementById("nama").value == ""){
			sMsg += ("\n* Picture Name cannot be empty");
		}
		if(document.getElementById("description").value == ""){
			sMsg += ("\n* Description cannot be empty");
		}
		if(thisext != ".jpg"){
			sMsg += ("\n* Your upload form contains an unapproved file name");
		}
		if(file.size > 500000){
			sMsg += ("\n* Your file " + file.name + " is " + file.size + " bytes in size, please reduce your file size less than 500.000 bytes");
		}
		if(sMsg != ""){
			document.getElementById('contact').reset();
			alert("Attention :\n" + sMsg);
			return false;
		}
		else
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


	
