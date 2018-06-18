function disable(id1, id2, radio) {
  document.getElementById(id1).disabled = radio.checked;
  if (radio.checked) {
    document.getElementById(id2).style.display = "none";
  }

}

function enable(id1, id2, radio) {
  document.getElementById(id1).disabled = !radio.checked;
  if (radio.checked) {
    document.getElementById(id2).style.display = "inherit";
  }
}

function sortTableAlphabetically(tableId, n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(tableId);
  switching = true;
  dir = "asc"; 

  while (switching) {
    switching = false;
    rows = table.getElementsByTagName("TR");

    for (i = 1; i < rows.length - 1; ++i) {
      shouldSwitch = false;

      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];

      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          shouldSwitch = true;
          break;
        }
      }
      else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          shouldSwitch = true;
          break;
        }
      }
    }
    
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      ++switchcount; 
    }
    else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function sortTableNumerically(tableId, n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(tableId);
  switching = true;
  dir = "asc"; 

  while (switching) {
    switching = false;
    rows = table.getElementsByTagName("TR");

    for (i = 1; i < rows.length - 1; ++i) {
      shouldSwitch = false;

      x = rows[i].getElementsByTagName("TD")[n].innerHTML;
      x = (x == "-") ? "0" : x;
      y = rows[i + 1].getElementsByTagName("TD")[n].innerHTML;
      y = (y == "-") ? "0" : y;

      if (dir == "asc") {
        if (Number(x) > Number(y)) {
          shouldSwitch = true;
          break;
        }
      }
      else if (dir == "desc") {
        if (Number(x) < Number(y)) {
          shouldSwitch = true;
          break;
        }
      }
    }
    
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      ++switchcount; 
    }
    else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

