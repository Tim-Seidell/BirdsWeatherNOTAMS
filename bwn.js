

// copyTemplate();

window.onload = function () {

    // Timezone
    document.getElementById('timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone;

    // NOTAM settings checkboxes
    document.getElementById("notam_hide_checkbox").addEventListener("click", toggleNOTAMHide);
    document.getElementById("notam_id_checkbox").addEventListener("click", toggleNOTAMID);
    document.getElementById("notam_valid_checkbox").addEventListener("click", toggleNOTAMValid);
    document.getElementById("notam_created_checkbox").addEventListener("click", toggleNOTAMCreated);
    
    // Functions to run on refresh
    // rebuildNOTAMs();
    // rebuildWeather();
    // rebuildAHAS();
    // generateTable();

    // toggleNOTAMHide();
    // toggleNOTAMID();
    // toggleNOTAMValid();
    // toggleNOTAMCreated();

    buildCards();
}

function toggleNOTAMHide() {
    const notam_hide_checkbox = document.getElementById("notam_hide_checkbox");
    var all_notam_hide_buttons = document.getElementsByClassName("notam-hide");
    
    if(!notam_hide_checkbox.checked) {
        for (let i = 0; i < all_notam_hide_buttons.length; i++) {
            all_notam_hide_buttons[i].style.display = "none";
        }
    } else {
        for (let i = 0; i < all_notam_hide_buttons.length; i++) {
            all_notam_hide_buttons[i].style.display = "inline";
        }
    }
}

function toggleNOTAMID() {
    const notam_id_checkbox = document.getElementById("notam_id_checkbox");
    var all_notam_ids = document.getElementsByClassName("notam_id");
    
    if(!notam_id_checkbox.checked) {
        for (let i = 0; i < all_notam_ids.length; i++) {
            all_notam_ids[i].style.display = "none";
        }
    } else {
        for (let i = 0; i < all_notam_ids.length; i++) {
            all_notam_ids[i].style.display = "inline";
        }
    }
}

function toggleNOTAMValid() {
    const notam_valid_checkbox = document.getElementById("notam_valid_checkbox");
    var all_notam_starts = document.getElementsByClassName("notam_start");
    var all_notam_ends = document.getElementsByClassName("notam_end");
    
    if(!notam_valid_checkbox.checked) {
        for (let i = 0; i < all_notam_starts.length; i++) {
            all_notam_starts[i].style.display = "none";
            all_notam_ends[i].style.display = "none";
        }
    } else {
        for (let i = 0; i < all_notam_starts.length; i++) {
            all_notam_starts[i].style.display = "inline";
            all_notam_ends[i].style.display = "inline";
        }
    }
}

function toggleNOTAMCreated() {
    const notam_created_checkbox = document.getElementById("notam_created_checkbox");
    var all_notam_createds = document.getElementsByClassName("notam_created");
    
    if(!notam_created_checkbox.checked) {
        for (let i = 0; i < all_notam_createds.length; i++) {
            all_notam_createds[i].style.display = "none";
        }
    } else {
        for (let i = 0; i < all_notam_createds.length; i++) {
            all_notam_createds[i].style.display = "inline";
        }
    }
}

function buildCards() {
    // Get all ICAOs from search
    const all_icao_p = document.getElementById("all_icaos");
    var all_icao = [];
    if(all_icao_p) {
        all_icao = all_icao_p.innerText.split(/[ ,]+/);
    }
    console.log(all_icao);

    // Make a card for each ICAO
    all_icao.forEach(icao => {
        copyTemplate(icao);
    });

    // Update data
    all_icao.forEach(icao => {
        // Change Titles
        document.getElementById(icao + "_title").innerText = icao.toUpperCase();
    
        // Insert METARs
        var metar_data = document.getElementsByClassName(icao + "_metar")[0].innerText;
        const metar_display = document.getElementById(icao + "_metar");
        if(metar_data == "") {
            metar_data = "NO DATA";
        }
        metar_display.innerText = metar_data;

        // Insert TAFs
        var taf_data = document.getElementsByClassName(icao + "_taf")[0].innerText;
        const taf_display = document.getElementById(icao + "_taf");
        if(taf_data == "") {
            taf_data = "NO DATA";
        }
        taf_display.innerText = taf_data;

        // Insert NOTAMs
        var notams = document.getElementsByClassName(icao + "_notam");
        const notam_display = document.getElementById(icao + "_notams");
        
        for (let notam of notams) {
            // notam.hidden = false;
            // notam_display.appendChild(notam);

            // Parent div
            const notam_div = document.createElement("div");
            notam_display.appendChild(notam_div);
            
            // Hide button
            const hide_button = document.createElement("button");
            hide_button.innerText = "Hide";
            hide_button.className = "notam-hide hide-button";
            hide_button.onclick = function() {
                let new_hidden_notam = notam_div.cloneNode(true);
                const hidden_div = document.getElementById(icao + "_hidden_notams");
                hidden_div.appendChild(new_hidden_notam);
                hide_button.parentElement.remove();
            };
            notam_div.appendChild(hide_button);

            // JS stuff to create NOTAM elements
            const notam_id = document.createElement("div");
            const notam_text = document.createElement("div");
            const notam_start = document.createElement("div");
            const notam_end = document.createElement("div"); 
            const notam_created = document.createElement("div");

            // NOTAM text
            var tokenized_notam = notam.innerText.replace(/[^\u0000-\u007F]/g, "'");
            const re = /(.*?) - (.*)\. (.*) UNTIL (.*)\. CREATED: (.*)/gm;

            for (const match of tokenized_notam.matchAll(re)) {       
                notam_id.innerText = " " + match[1];
                notam_text.innerText = " - " + match[2];
                notam_start.innerText = " " + match[3];
                notam_end.innerText = " - " + match[4];
                notam_created.innerText = " Created: " + match[5];
            }
            
            notam_id.classList.add("notam_id");
            notam_text.classList.add("notam_text");
            notam_start.classList.add("notam_start");
            notam_end.classList.add("notam_end");
            notam_created.classList.add("notam_created");
                            
            notam_id.style.display = "inline";
            notam_text.style.display = "inline";
            notam_start.style.display = "inline";
            notam_end.style.display = "inline";
            notam_created.style.display = "inline";
            
            notam_div.appendChild(notam_id);
            notam_div.appendChild(notam_text);
            notam_div.appendChild(notam_start);
            notam_div.appendChild(notam_end);
            notam_div.appendChild(notam_created);
        }
    });
}

function copyTemplate(icao) {
    // Make a copy
    let icao_card = document.getElementById("ICAO_card");
    let new_card = icao_card.cloneNode(true);
    new_card.id = icao + "_card";
    new_card.hidden = false;

    // Change only that copy
    var all_editable_fields = new_card.getElementsByClassName("editable");
    for (let item of all_editable_fields) {
        const regex = /ICAO*/i;
        item.id = item.id.replace(regex, icao);
        console.log(item.id);
    }

    // Add to DOM
    document.body.appendChild(new_card);
}

function rebuildNOTAMs() {
    const notam_parent_div = document.getElementById("notams");
    const all_notams = document.getElementsByClassName("notam");
    var icao_list = new Set();

    // Get list of all icaos
    for (let i = 0; i < all_notams.length; i++) {
        var classes = all_notams[i].className.split(" ");
        icao_list.add(classes[1]);
    }
    
    // Rebuild NOTAMs with buttons
    icao_list.forEach(function(icao_notam) {
        // ICAO Title
        const notam_title = document.createElement("p");
        notam_title.innerText = icao_notam.split("_")[0].toUpperCase() + " NOTAMs:";
        notam_parent_div.appendChild(notam_title);

        // NOTAMs for that ICAO
        const notams = document.getElementsByClassName(icao_notam);
        for (let i = 0; i < notams.length; i++) {
            // Parent div
            const notam_div = document.createElement("div");
            notam_parent_div.appendChild(notam_div);
            
            // Hide button
            const hide_button = document.createElement("button");
            hide_button.innerText = "Hide";
            hide_button.className = "notam-hide hide-button";
            hide_button.onclick = function() { hide_button.parentElement.remove(); };
            notam_div.appendChild(hide_button);

            // JS stuff to create NOTAM elements
            const notam_id = document.createElement("div");
            const notam_text = document.createElement("div");
            const notam_start = document.createElement("div");
            const notam_end = document.createElement("div"); 
            const notam_created = document.createElement("div");

            // NOTAM text
            var notam = notams[i].innerText.replace(/[^\u0000-\u007F]/g, "'");
            const re = /(.*?) - (.*)\. (.*) UNTIL (.*)\. CREATED: (.*)/gm;

            for (const match of notam.matchAll(re)) {       
                notam_id.innerText = " " + match[1];
                notam_text.innerText = " - " + match[2];
                notam_start.innerText = " " + match[3];
                notam_end.innerText = " - " + match[4];
                notam_created.innerText = " Created: " + match[5];
            }
            
            notam_id.classList.add("notam_id");
            notam_text.classList.add("notam_text");
            notam_start.classList.add("notam_start");
            notam_end.classList.add("notam_end");
            notam_created.classList.add("notam_created");
                            
            notam_id.style.display = "inline";
            notam_text.style.display = "inline";
            notam_start.style.display = "inline";
            notam_end.style.display = "inline";
            notam_created.style.display = "inline";
            
            notam_div.appendChild(notam_id);
            notam_div.appendChild(notam_text);
            notam_div.appendChild(notam_start);
            notam_div.appendChild(notam_end);
            notam_div.appendChild(notam_created);
        }
    });
}

function rebuildWeather() {
    const weather_parent_div = document.getElementById("weather");
    var all_icao_string = document.getElementById("all_icaos").innerText;

    // Remove whitespace and create list
    all_icao_string = all_icao_string.replace(/\s/g, "");
    const all_icao = all_icao_string.split(",");

    all_icao.forEach(function(icao) {
        const icao_weather_title = document.createElement("p");
        icao_weather_title.innerText = icao.toUpperCase() + " Weather:";
        weather_parent_div.appendChild(icao_weather_title);

        const icao_metar = document.createElement("p");
        icao_metar.innerText = document.getElementsByClassName(icao + "_metar")[0].innerText;
        weather_parent_div.appendChild(icao_metar);

        const icao_taf = document.createElement("p");
        icao_taf.innerText = document.getElementsByClassName(icao + "_taf")[0].innerText;
        weather_parent_div.appendChild(icao_taf);
    });
}

function rebuildAHAS() {
    const ahas_div = document.getElementById("birds");
    const ahas_table = document.getElementById("ahas_table");
    ahas_div.appendChild(ahas_table);
}

function generateTable(skip) {
    // Get all ICAOs searched
    var all_icao_string = document.getElementById("all_icaos").innerText;
    all_icao_string = all_icao_string.replace(/\s/g, "");
    const all_icao = all_icao_string.split(",");

    // Create table
    const tbl = document.createElement("table");
    const tblBody = document.createElement("tbody");
    const th = ["Hide", "ID", "Text", "Start", "End", "Created"];

    // Create Header row
    const header_row = document.createElement("tr");

    th.forEach(function(header) {
        const cell = document.createElement("td");
        const cellText = document.createTextNode(header);
        cell.className = "notam_" + header.toLowerCase();
        cell.appendChild(cellText);
        header_row.appendChild(cell);
    });

    tblBody.appendChild(header_row);

    // Create rows for all notams
    all_icao.forEach(function(icao) {
        const notams = document.getElementsByClassName(icao + "_notam");

        for (let i = 0; i < notams.length; i++) {
            const tr = document.createElement("tr");
            const notam_array = [];
            var notam = notams[i].innerText.replace(/[^\u0000-\u007F]/g, "'");
            const re = /(.*?) - (.*)\. (.*) UNTIL (.*)\. CREATED: (.*)/gm;

            for (const match of notam.matchAll(re)) {  
                notam_array.push(match[1], match[2], match[3], match[4], match[5])
            }

            const cell = document.createElement("td");
            const hide_button = document.createElement("button");
            hide_button.innerText = "Hide";
            hide_button.onclick = function() { hide_button.parentElement.parentElement.remove(); };
            cell.appendChild(hide_button);
            tr.appendChild(cell);

            for (let i = 0; i < notam_array.length; i++) {
                const cell = document.createElement("td");
                const cellText = document.createTextNode(notam_array[i]);
                cell.className = "notam_" + th[i+1].toLowerCase();
                cell.appendChild(cellText);
                tr.appendChild(cell);
            }
            tblBody.appendChild(tr);
        }
    });

    tbl.appendChild(tblBody);
    document.body.appendChild(tbl);
}

document.getElementById('btnSwitch').addEventListener('click',()=>{
    if (document.documentElement.getAttribute('data-bs-theme') == 'dark') {
        document.documentElement.setAttribute('data-bs-theme','light')
    }
    else {
        document.documentElement.setAttribute('data-bs-theme','dark')
    }
})