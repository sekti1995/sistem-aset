	function findPrinter(name) {
		// Get printer name from input box
		var p = document.getElementById('printer');
		if (name) {
			p.value = name;
		}
		
		if (isLoaded()) {
			// Searches for locally installed printer with specified name
			qz.findPrinter(p.value);
			
			// Automatically gets called when "qz.findPrinter()" is finished.
			window['qzDoneFinding'] = function() {
				var p = document.getElementById('printer');
				var printer = qz.getPrinter();
				
				// Alert the printer name to user
				alert(printer !== null ? 'Printer found: "' + printer + 
					'" after searching for "' + p.value + '"' : 'Printer "' + 
					p.value + '" not found.');
				
				// Remove reference to this function
				window['qzDoneFinding'] = null;
			};
		}
	}
    function monitorPrinting() {
	var applet = document.jzebra;
	if (applet != null) {
	   if (!applet.isDonePrinting()) {
	      window.setTimeout('monitorPrinting()', 100);
	   } else {
	      var e = applet.getException();
	   }
	} else {
        }
      }
    function monitorFinding() {
	var applet = document.jzebra;
	if (applet != null) {
	   if (!applet.isDoneFinding()) {
	      window.setTimeout('monitorFinding()', 100);
	   } else {
	      var printer = applet.getPrinter();
           //   alert(printer == null ? "Printer not found" : "Printer \"" + printer + "\" found");
	   }
	} else {
          //  alert("Applet not loaded!");
        }
      }
	  
	function useDefaultPrinter() {
		if (isLoaded()) {
			// Searches for default printer
			qz.findPrinter();
			
			// Automatically gets called when "qz.findPrinter()" is finished.
			window['qzDoneFinding'] = function() {
				// Alert the printer name to user
				var printer = qz.getPrinter();
				alert(printer !== null ? 'Default printer found: "' + printer + '"':
					'Default printer ' + 'not found');
				
				// Remove reference to this function
				window['qzDoneFinding'] = null;
			};
		}
	}