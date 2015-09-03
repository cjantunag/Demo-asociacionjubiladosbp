/////// <tr onmouseover="setPointer(this, 'over', '#CCCCCC', '#CCFFCC', '#FFCC99')" onmouseout="setPointer(this, 'out', '#CCCCCC', '#CCFFCC', '#FFCC99')" onmousedown="setPointer(this, 'click', '#CCCCCC', '#CCFFCC', '#FFCC99')">


/**
 * Displays an error message if an element of a form hasn't been completed and
 * should be
 * @param   object   the form
 * @param   string   the name of the form field to put the focus on
 * @return  boolean  whether the form field is empty or not
 */
function emptyFormElements(theForm, theFieldName) {
    var isEmpty  = 1;
    var theField = theForm.elements[theFieldName];
    // Whether the replace function (js1.2) is supported or not
    var isRegExp = (typeof(theField.value.replace) != 'undefined');

    if (!isRegExp) {
        isEmpty      = (theField.value == '') ? 1 : 0;
    } else {
        var space_re = new RegExp('\\s+');
        isEmpty      = (theField.value.replace(space_re, '') == '') ? 1 : 0;
    }
    if (isEmpty) {
        theForm.reset();
        theField.select();
        alert(errorMsg0);
        theField.focus();
        return false;
    }
    return true;
}

/**
 * Ensures a value submitted in a form is numeric and is in a range
 * @param   object   the form
 * @param   string   the name of the form field to check
 * @param   integer  the minimum authorized value
 * @param   integer  the maximum authorized value
 * @return  boolean  whether a valid number has been submitted or not
 */
function checkFormElementInRange(theForm, theFieldName, min, max) {
    var theField         = theForm.elements[theFieldName];
    var val              = parseInt(theField.value);

    if (typeof(min) == 'undefined') min = 0;
    if (typeof(max) == 'undefined') max = Number.MAX_VALUE;

    // It's not a number
    if (isNaN(val)) {
        theField.select();
        alert(errorMsg1);
        theField.focus();
        return false;
    } else if (val < min || val > max) {
    // It's a number but it is not between min and max
        theField.select();
        alert(val + errorMsg2);
        theField.focus();
        return false;
    } else {
    // It's a valid number
        theField.value = val;
    }
    return true;
}

/**
 * Sets/unsets the pointer in a table
 * @param   object   the table row
 * @param   string   the action calling this script (over, out or click)
 * @param   string   the default background color
 * @param   string   the color to use for mouseover
 * @param   string   the color to use for marking a row
 * @return  boolean  whether pointer is set or not
 */
function setPointer(theRow, theAction, theDefaultColor, thePointerColor, theMarkColor) {
    var theCells = null;
    // 1. Pointer and mark feature are disabled or the browser can't get the row -> exits
    if ((thePointerColor == '' && theMarkColor == '') || typeof(theRow.style) == 'undefined') return false;

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    } else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    } else {
        return false;
    }

    // 3. Gets the current color...
    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;
    // 3.1 ... with DOM compatible browsers except Opera that does not return valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    } else {
    // 3.2 ... with other browsers
        currentColor = theCells[0].style.backgroundColor;
        domDetect    = false;
    }

    // 4. Defines the new color
    // 4.1 Current color is the default one
    if (currentColor == '' || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor = thePointerColor;
        } else if (theAction == 'click' && theMarkColor != '') {
            newColor = theMarkColor;
        }
    } else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()) {
    // 4.1.2 Current color is the pointer one
        if (theAction == 'out') {
            newColor = theDefaultColor;
        } else if (theAction == 'click' && theMarkColor != '') {
            newColor = theMarkColor;
        }
    } else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
    // 4.1.3 Current color is the marker one
        if (theAction == 'click') {
            newColor = (thePointerColor != '') ? thePointerColor : theDefaultColor;
        }
    }

    // 5. Sets the new color...
    if (newColor) {
        var c = null;
        // 5.1 ... with DOM compatible browsers except Opera
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } // end for
        } else {
        // 5.2 ... with other browsers
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    }

    return true;
}

/**
 * Checks/unchecks all Checkboxes
 * @param   string   the form name
 * @param   boolean  whether to check or to uncheck the element
 * @return  boolean  always true
 */
function setCheckboxes(the_form, do_check) {
    var elts      = document.forms[the_form].elements['selected_tbl[]'];
    var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

    if (elts_cnt) {
        for (var i = 0; i < elts_cnt; i++) {
            elts[i].checked = do_check;
        }
    } else {
        elts.checked        = do_check;
    }
    return true;
}

/**
  * Checks/unchecks all options of a <select> element
  * @param   string   the form name
  * @param   string   the element name
  * @param   boolean  whether to check or to uncheck the element
  * @return  boolean  always true
  */
function setSelectOptions(the_form, the_select, do_check) {
    var selectObject = document.forms[the_form].elements[the_select];
    var selectCount  = selectObject.length;

    for (var i = 0; i < selectCount; i++) {
        selectObject.options[i].selected = do_check;
    }

    return true;
}
