
/* http://www.javascriptkit.com/javatutors/arraysort2.shtml */
        
/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false, REDIPS: true */

/* enable strict mode */
"use strict";

// define redipsInit variable
var redipsInit;
var starting;
var ending;

// redips initialization
redipsInit = function () {
	var rd = REDIPS.drag,	// reference to the REDIPS.drag class
		divDrag = document.getElementById('redips-drag'); // reference to the drag region
	// DIV container initialization
	rd.init();
        
        // One div per td
        rd.dropMode = 'single';
        
        // this function (event handler) is called after element is moved
        rd.event.moved = function () {
                var div1,		// DIV elements inside DIV id="drag" (collection)
			div2 = [],	// references of DIV elements prepared from collection
			cb, i, j;	// element Id

                // Select all ticked checkboxes, store them in div2...
		// collect DIV elements from drag region
		div1 = divDrag.getElementsByTagName('div');
		// loop through collected DIV elements
		for (i = 0, j = 0; i < div1.length; i++) {
			// locate checkbox inside DIV element
			cb = div1[i].getElementsByTagName('input');
			// if checkbox inside DIV element is checked
			if (cb.length > 0 && cb[0].checked === true){
				// save reference of DIV element to the div2 array
				div2[j] = div1[i];
				// increment counter j
				j++;
			}
		}
                
                // ...and sort them by name
                div2.sort(function(a, b){
                    var nameA=a.id.toLowerCase(), nameB=b.id.toLowerCase();
                    if (nameA < nameB) //sort string ascending
                        return -1;
                    if (nameA > nameB)
                        return 1;
                    return 0; //default return value (no sorting)
                });
                
                starting = div2[0].parentNode.id;
        };

        // this function (event handler) is called after element is dropped
	rd.event.dropped = function (el) {
		var div1,		// DIV elements inside DIV id="drag" (collection)
			div2 = [],	// references of DIV elements prepared from collection
			cb, i, j;	// element Id     
                var t1, t2, t3, t4, y=el.getElementsByTagName('div');
                var pos = rd.getPosition();
                var trgts = [];
                var bl=true;

                // Select all ticked checkboxes, store them in div2...
		// collect DIV elements from drag region
		div1 = divDrag.getElementsByTagName('div');
		// loop through collected DIV elements
		for (i = 0, j = 0; i < div1.length; i++) {
			// locate checkbox inside DIV element
			cb = div1[i].getElementsByTagName('input');
			// if checkbox inside DIV element is checked
			if (cb.length > 0 && cb[0].checked === true) {
				// uncheck checkbox
				cb[0].checked = false;
				// save reference of DIV element to the div2 array
				div2[j] = div1[i];
				// increment counter j
				j++;
			}
		}
                
                if(j===0 || y[0].getElementsByTagName('input')[0].disabled===true){
                    rd.moveObject({
                        obj: y[0],
                        target: [pos[3], pos[4], pos[5]]
                    });
                }
                else{
                    // ...and sort them by name
                    div2.sort(function(a, b){
                        var nameA=a.id.toLowerCase(), nameB=b.id.toLowerCase();
                        if (nameA < nameB) //sort string ascending
                            return -1;
                        if (nameA > nameB)
                            return 1;
                        return 0; //default return value (no sorting)
                    });

                    // Get the id of the tds that are going to be covered
                    t1 = div2[0].parentNode.id;
                    t2 = t1.substring(0, 2);
                    for(i=1;i<div2.length;i++){
                        t3 = parseInt(t2);
                        t3 = t3+i;
                        t4 = t3.toString();
                        if(t3<10){
                            t4 = "0".concat(t4);
                        }
                        t4 = t4.concat("_",t1.substring(3, 4));
                        trgts[i-1] = t4;
                    }

                    // Check if under space
                    if(pos[1]+div2.length-2>document.getElementById('hours_c').innerHTML){
                        bl=false;
                    }
                    else{
                        // Check if space is clear
                        for(i=1;i<div2.length;i++){
                            if(document.getElementById(trgts[i-1]).innerHTML!==""){
                                bl=false;
                                break;
                            }
                        }
                    }
                    
                    // Try to move the elements
                    if((bl===true || div2.length===1) && y[0].id===div2[0].id){
                        for (i = 0 ; i < div2.length; i++) {
                                // element will be moved to the dropped table cell
                                rd.moveObject({
                                    obj: div2[i],
                                    target: [pos[0], pos[1]+i, pos[2]]
                                });
                        }
                        ending = div2[0].parentNode.id;
                        document.getElementById('start_end').innerHTML = starting.concat(" ",ending);
                    }
                    else{
                        rd.moveObject({
                            obj: y[0],
                            target: [pos[3], pos[4], pos[5]]
                        });
                    }
                       
                }
                
                // Enable all checkboxes
                // collect DIV elements from drag region
                div1 = divDrag.getElementsByTagName('div');
                // loop through collected DIV elements
                for (i = 0 ; i < div1.length; i++) {
                    // locate checkbox inside DIV element
                        cb = div1[i].getElementsByTagName('input');
                        // if checkbox inside DIV element is checked
                        if (cb.length > 0) {
                            // enable checkbox
                            cb[0].disabled = false;
                        }
                }
	};  
};

// add onload event listener
if (window.addEventListener) {
	window.addEventListener('load', redipsInit, false);
}
else if (window.attachEvent) {
	window.attachEvent('onload', redipsInit);
}