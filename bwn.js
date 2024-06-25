// Timezone
document.getElementById('timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone;

// NOTAM settings checkboxes
document.getElementById("notam_hide_checkbox").addEventListener("click", toggleNOTAMHide);
document.getElementById("notam_id_checkbox").addEventListener("click", toggleNOTAMID);
document.getElementById("notam_valid_checkbox").addEventListener("click", toggleNOTAMValid);
document.getElementById("notam_created_checkbox").addEventListener("click", toggleNOTAMCreated);

toggleNOTAMHide();
toggleNOTAMID();
toggleNOTAMValid();
toggleNOTAMCreated();

buildCards();

document.getElementById('btnSwitch').addEventListener('click',()=>{
    const dark_mode_switch = document.getElementById("btnSwitch");

    if (dark_mode_switch.checked) {
        document.documentElement.setAttribute('data-bs-theme','dark')
    }
    else {
        document.documentElement.setAttribute('data-bs-theme','light')
    }
})

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

    // Make a card for each ICAO
    all_icao.forEach(icao => {
        copyTemplate(icao);
    });

    // Update data
    all_icao.forEach(icao => {
        // Change Titles
        document.getElementById(icao + "_title").innerText = icao.toUpperCase();
    
        // Insert AHAS
        for(var i = 1; i < 13; i++) {
            // Times
            const icao_time_display = document.getElementById(icao + "_ahas_time_" + i);
            var ahas_time = document.getElementById("time_" + i).innerText;

            icao_time_display.innerText = ahas_time;

            // Risks
            const icao_risk_display = document.getElementById(icao + "_ahas_risk_" + i);
            var ahas_risk = document.getElementById(icao + "_risk_" + i).innerText;

            // Background color
            if(ahas_risk == "LOW") {
                icao_risk_display.style.backgroundColor = "green";
            } else if (ahas_risk == "MOD") {
                icao_risk_display.style.backgroundColor = "yellow";
                icao_risk_display.style.color = "black";
            } else if (ahas_risk == "SEV") {
                icao_risk_display.style.backgroundColor = "red";
            }

            icao_risk_display.innerText = ahas_risk;
        }

        // Insert METARs
        var metar_data = document.getElementsByClassName(icao + "_metar")[0].innerText;
        const metar_display = document.getElementById(icao + "_metar");

            // Print "NO DATA" if no METAR is reported
        if(metar_data == "") {
            metar_data = "NO DATA";
        }
        metar_display.innerText = metar_data;

            // Enhanced SA from METAR
        const metar_stats_display = document.getElementById(icao + "_metar_stats");
        const re = /([A-Z]{4})* ([0-9]{2})([0-9]{4})Z (AUTO )*(?:([0-9]{3})([0-9]{2})KT|([0-9]{3})([0-9]{2})G([0-9]{2})KT|VRB([0-9]{2})KT) (?:([0-9]{3})V([0-9]{3}) )?([0-9]{2})SM (?:([A-Z]{3}(?:[0-9]{3})?) )?(?:([A-Z]{3}(?:[0-9]{3})?) )?(?:([A-Z]{3}(?:[0-9]{3})?) )?(?:([A-Z]{3}(?:[0-9]{3})?) )?(?:([A-Z]{3}(?:[0-9]{3})?) )?(?:([A-Z]{3}(?:[0-9]{3})?) )?([0-9]{2})\/([0-9]{2}) A([0-9]{4})/gm;


        /* The abomination of a regex above, captures the following groups from a METAR:
        *   1 - Station identifier
        *   2 - Number the day of the month
        *   3 - UTC time of observation
        *   4 - AUTO generated
        *   5-6 - Wind direction and speed (14010KT)
        *   7-9 - Wind direction, speed, and gust (14010G15KT)
        *   10 - Variable wind speed (VRB05KT)
        *   11-12 - Varying wind directions (130V310)
        *   13 - Visibility (currently doesn't support fractions)
        *   14-19 - Cloud layers
        *   20 - Temperature (C)
        *   21 - Dew Point (C)
        *   22 - Altimeter setting 
        */
        var metar_tokens = [...metar_data.matchAll(re)];
        metar_tokens = metar_tokens[0];
        const observation_date = metar_tokens[2];
        const observation_hour = metar_tokens[3].slice(0,2);
        const observation_minute = metar_tokens[3].slice(2);

        const observation_time = new Date();
        const current_time = new Date();
        observation_time.setUTCDate(observation_date);
        observation_time.setUTCHours(observation_hour);
        observation_time.setUTCMinutes(observation_minute);
        observation_time.setSeconds(0);
        observation_time.setMilliseconds(0);

        const time_since_observation = Math.round((current_time - observation_time) / 1000 / 60);
        console.log(time_since_observation + " min ago");
        metar_stats_display.innerText = " (" + time_since_observation + " min ago)";

        // Insert TAFs
                // Regex to match all new lines
                // ^(?:.*?\b(max\d+)\b)?.*
        var taf_data = document.getElementsByClassName(icao + "_taf")[0].innerText;
        const taf_display = document.getElementById(icao + "_taf");
        if(taf_data == "") {
            taf_data = "NO DATA";
        }
        taf_display.innerText = taf_data;

        // Insert NOTAMs
        var notams = document.getElementsByClassName(icao + "_notam");
        const notam_display = document.getElementById(icao + "_notams");
        const hidden_notam_display = document.getElementById(icao + "_hidden_notams");
        
        for (let notam of notams) {
            // Parent div
            const notam_div = document.createElement("div");
            notam_div.className = "row pb-1";
            notam_display.appendChild(notam_div);
            
            // Hide button
            const hide_button = document.createElement("div");
            hide_button.style.display = "inline";
            hide_button.innerText = "Hide";
            hide_button.innerHTML = "<i class=\"fa-regular fa-eye-slash fs-3\"></i>";
            hide_button.className = "notam-hide col-1";
            hide_button.onclick = function() {
                if(notam_div.parentNode.id == icao + "_notams") {
                    hidden_notam_display.appendChild(notam_div);
                    hide_button.innerHTML = "<i class=\"fa-regular fa-eye fs-3\"></i>";
                } else if(notam_div.parentNode.id == icao + "_hidden_notams") {
                    notam_display.appendChild(notam_div);
                    hide_button.innerHTML = "<i class=\"fa-regular fa-eye-slash fs-3\"></i>";
                }
            };
            notam_div.appendChild(hide_button);

            // JS stuff to create NOTAM elements
            const full_notam = document.createElement("div");
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
     
            full_notam.classList.add("col-11");
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
            
            notam_div.appendChild(hide_button);
            full_notam.appendChild(notam_id);
            full_notam.appendChild(notam_text);
            full_notam.appendChild(notam_start);
            full_notam.appendChild(notam_end);
            full_notam.appendChild(notam_created);
            notam_div.append(full_notam);
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
    }

    // Add to DOM
    document.body.appendChild(new_card);
}

// function fits(temperature, dewpoint) {

//     // https://static.e-publishing.af.mil/production/1/af_sg/publication/dafi48-151/dafi48-151.pdf
//     const fits_table = [
//         [-1,-1,-1,-1,-1,82,83,84,86,87,88,90,91,92,94,95,96,98,99],
//         [-1,-1,-1,-1,-1,82,83,85,86,87,89,90,91,93,94,95,96,98,99],
//         [-1,-1,-1,-1,-1,82,83,85,86,87,89,90,91,93,94,95,97,98,99],
//         [-1,-1,-1,-1,-1,82,84,85,86,88,89,90,92,93,94,96,97,98,99],
//         [-1,-1,-1,-1,-1,83,84,85,87,88,89,91,92,93,95,96,97,98,100],
//         [-1,-1,-1,-1,-1,83,84,85,87,88,90,91,92,93,95,96,97,99,100],
//         [76,77,79,80,82,83,84,86,87,88,90,91,92,94,95,96,98,99,100],
//         [76,78,79,81,82,83,84,86,87,88,90,91,93,94,95,97,98,99,100],
//         [77,78,80,81,82,84,85,86,87,88,90,92,93,94,96,97,98,99,101],
//         [77,79,80,81,83,84,85,87,88,89,91,92,93,95,96,97,98,99,101],
//         [77,79,80,81,83,84,85,87,88,89,91,92,94,95,96,97,98,99,101],
//         [78,79,80,81,83,84,85,87,88,89,91,92,94,95,96,97,98,99,101],
//         [79,80,81,82,83,85,86,87,88,90,91,93,94,95,96,98,99,100,102],
//         [79,],
//         [80,],
//         [81,],
//         [81,],
//         [82,],
//         [82,],
//         [83,],
//         [84,],
//         [84,],
//         [84,],
//         [85,],
//         [86,],
//         [87,],
//         [88,],
//         [89,],
//         [90,],
//         [91,],
//         [92,],
//         [93,],
//         [94,],
//         [95,],
//         [97,],
//         [98,],
//         [99,],
//         [101,],
//         [102,],
//         [103,],
//         [105,],
//     ];


//     // Convert Celsius to Fahrenheit
//     temperature = Math.round((temperature * 9/5) + 32);
//     dewpoint = Math.round((dewpoint * 9/5) + 32);

//     // Convert temperatures to a multiple of 2
//     temperature = temperature % 2 == 0 ? temperature : temperature - 1;
//     dewpoint = dewpoint % 2 == 0 ? dewpoint : dewpoint - 1;

//     // Clamp temperatures between min/max table indicies
//     temperature = clamp(temperature, 80, 116);
//     dewpoint = clamp(dewpoint, 10, 90);

//     // Convert temperatures to array indices
//     temperature -= 80;
//     dewpoint -= 10

//     // Return FITS
//     if(fits_table[dewpoint][temperature] == -1) {
//         return "Invalid";
//     } else if (fits_table[dewpoint][temperature] < 90) {
//         return "Norm";
//     } else if(fits_table[dewpoint][temperature] < 100) {
//         return "Caution";
//     } else {
//         return "Danger";
//     }
// }

function clamp(value, min, max) {
    if(value < min) {
        return min;
    } else if (value > max) {
        return max;
    } else {
        return value;
    }
}