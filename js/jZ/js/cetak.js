      function findPrinter() {
         var applet = document.jzebra;
         if (applet != null) {
            applet.findPrinter("zebra");
         }
         monitorFinding();
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
      var applet = document.jzebra;
         if (applet != null) {
            applet.findPrinter();
         }
         //monitorFinding();
      }	