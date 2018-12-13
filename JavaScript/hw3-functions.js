/**
 * Created by Hans Dulimarta
 * 
 * Modified by Brandon Conn
 * CIS 371
 * FALL 2018
 */

/**
 * Given the ID of a node {rootId}, find all its descendent tableHome having
 * its attribute id set and then change their class to {klazName}.
 * The function returns the number of such tableHome found.
 *
 * @param rootId    the ID of the "root" element to begin searching
 * @param klazName  the class to assign to each descendant whose id attrib 
 *                  is set.
 * @returns {number}
 */
function findElementsWithId(rootId, klazName) {

  /* complete this function */
  var elementID = document.getElementById(rootId).children
  var count = 0;
  for (key of elementID) {
    if(key.id){   
 	key.className += klazName;
	count++;
    }
  }
  return count;
}

/**
 * The following function finds all tableHome with attribute 'data-gv-row' (or
 * 'data-gv-column') and create a table of the desired dimension as a child of
 * the element.
 *
 * @returns NONE
 */
function createTable() {

  /* complete this function */
  var tableHome = document.querySelectorAll("div.table-home");

  var rowNum = tableHome[0].getAttribute("data-gv-row");
  var colNum = tableHome[0].getAttribute("data-gv-column");

  var dummyText = new LoremIpsum();
  let table = document.createElement("table");

  let headerRow = document.createElement("tr");
  for (var l = 1; l <= colNum; l++) {
    tableHeader = document.createElement("th");
    headingName = document.createTextNode("Heading " + (l));
    tableHeader.appendChild(headingName);
    headerRow.appendChild(tableHeader);
  }

  table.appendChild(headerRow);

  for (var j = 0; j < rowNum; j++) {
    let row = document.createElement("tr");
    for (var k = 0; k < colNum; k++) {
      let col = document.createElement("td");
      col.appendChild(document.createTextNode(dummyText.generate(3)));
      row.appendChild(col);
    }
    table.appendChild(row);
  }
  tableHome[0].appendChild(table);
}
