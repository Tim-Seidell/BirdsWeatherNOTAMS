<!DOCTYPE html>
<html lang="en">
    <head>
        <title>BWN</title>
        <!-- <link rel="icon" type="image/x-icon" href="https://hpanel.hostinger.com/favicons/hostinger.png"> -->
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="Default page" name="description">
        <meta content="width=device-width, initial-scale=1" name="viewport">
    </head>
    <style>
        body {
            touch-action: manipulation;
        }

        td {
            border: 1px solid black;
            word-wrap: break-word;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            text-align: center;
        }

        th {
            border: 1px solid black;
            word-wrap: break-word;
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
            margin: auto;
        }

        .hide-button {
            height: 3rem;
            margin: 0.2rem;
        }
    </style>
    <body>
        <form method="post" action="index.php" style="margin: 1rem; margin: auto; width: fit-content;">
            <input type="text" name="icao" placeholder="ICAO, ICAO, ICAO..." style="font-size: 18px;">
            <input type="text" id="timezone" name="timezone" hidden>
            <input type="submit" value="Enter" name="submit" style="font-size: 18px;">
        </form>
        <div id="birds"></div>
        <div id="weather"></div>
        <div id="notams">
            <input type="checkbox" name="hide" id="notam_hide_checkbox" checked>
            <label for="hide"> Hide</label>
            <input type="checkbox" name="id" id="notam_id_checkbox">
            <label for="id"> IDs</label>
            <input type="checkbox" name="valid" id="notam_valid_checkbox" checked>
            <label for="valid"> Valid</label>
            <input type="checkbox" name="created" id="notam_created_checkbox">
            <label for="created"> Created</label>
        </div>
    </body>
    <?php
        // Get ICAO from POST variables
        if(isset($_POST['icao'])) {
            $str = $_POST['icao'];
            $pattern = "/([0-9A-Za-z]{4})/";
            
            echo "<p id=\"all_icaos\" hidden>" . $_POST['icao'] . "</p>";

            // Look for 4-digit/character sequences
            if (preg_match_all($pattern, $str, $matches)) {
                
                // AHAS table
                getAHAS($matches[1]);
                
                // METAR/TAF
                foreach($matches[1] as &$value) {
                    getMETAR($value);
                    getTAF($value);
                 }
                
                // NOTAMS
                foreach($matches[1] as &$value) {
                     getNOTAMS($value);
                }
            }
        } 
        
        function getAHAS($icao_list) {
            echo "<table id=\"ahas_table\" style=\"border-collapse: collapse;\">";
            // Read JSON file
            $json = file_get_contents('icao.json'); 
            $json_data = json_decode($json,true); 
            $ahas_list = array();

            // Lookup, "UTF"-ify, and store ICAO full names
            foreach($icao_list as $icao) {
                // Read JSON file
                $json = file_get_contents('icao.json'); 
                $json_data = json_decode($json,true); 
                
                // Lookup icao full name
                $icao_full_name =  $json_data[strtoupper($icao)];
                
                // Replace spaces with "utf" spaces
                $pattern = "/ /";
                $icao_url_full_name = preg_replace($pattern, "%20", $icao_full_name);

                // Add to array
                array_push($ahas_list, $icao_url_full_name);
            }
            
            // Build AHAS table
            for ($icao = 0; $icao < count($ahas_list); $icao++) {
                $xml = getAHASfromXML($ahas_list[$icao]);

                if($icao == 0) {
                    echo "<tr>";
                    echo "<td style=\"border: 1px solid black;\"></td>";
                    
                    // DateTime row
                    for($table_index = 0; $table_index <= 11; $table_index++) {
                        if($table_index == 0) {
                            $datetime = $xml->NewDataSet->Table->DateTime;
                        } else {
                            $table = "Table" . $table_index;
                            $datetime = $xml->NewDataSet->$table->DateTime;
                        }

                        // $t = strtotime($datetime);
                        // $datetime = date('Hi',$t);

                        echo "<td style=\"border: 1px solid black;\">" . GmtTimeToLocalTime($datetime) . "</td>";
                        // echo "<td style=\"border: 1px solid black; padding: 0.5rem\">" . $datetime . "</td>";
                    }
                    echo "</tr>";

                    // AHAS risk for first in the list
                    echo "<tr>";
                    echo "<td style=\"border: 1px solid black;\">" . strtoupper($icao_list[$icao]) . "</td>";
                    for($table_index = 0; $table_index <= 11; $table_index++) {
                        if($table_index == 0) {
                            $ahas_risk = $xml->NewDataSet->Table->AHASRISK;
                        } else {
                            $table = "Table" . $table_index;
                            $ahas_risk = $xml->NewDataSet->$table->AHASRISK;
                        }

                        // Shorten MODERATE and SEVERE to three letters and set cell background color
                        if($ahas_risk == "MODERATE") {
                            $ahas_risk = "MOD";
                            $cell_color = "yellow";
                        } else if ($ahas_risk == "SEVERE") {
                            $ahas_risk = "SEV";
                            $cell_color = "red";
                        } else {
                            $cell_color = "green";
                        }

                        echo "<td style=\"border: 1px solid black; background-color: " . $cell_color . ";\">" . $ahas_risk . "</td>";
                    }
                    echo "</tr>";
                } else {
                    // AHAS risk for the rest of the list
                    echo "<tr>";
                    echo "<td style=\"border: 1px solid black;\">" . strtoupper($icao_list[$icao]) . "</td>";
                    for($table_index = 0; $table_index <= 11; $table_index++) {
                        if($table_index == 0) {
                            $ahas_risk = $xml->NewDataSet->Table->AHASRISK;
                        } else {
                            $table = "Table" . $table_index;
                            $ahas_risk = $xml->NewDataSet->$table->AHASRISK;
                        }

                        // Shorten MODERATE and SEVERE to three letters and set cell background color
                        if($ahas_risk == "MODERATE") {
                            $ahas_risk = "MOD";
                            $cell_color = "yellow";
                        } else if ($ahas_risk == "SEVERE") {
                            $ahas_risk = "SEV";
                            $cell_color = "red";
                        } else {
                            $cell_color = "green";
                        }

                        echo "<td style=\"border: 1px solid black; background-color: " . $cell_color . ";\">" . $ahas_risk . "</td>";
                    }
                    echo "</tr>";
                }
            }
            echo "</table>";
        }

        // Helper for getAHAS()
        function getAHASfromXML($icao) {
            // Get UTC month, day, and hour
            $month = gmdate("n");
            $day = gmdate("j");
            $hour = gmdate("G");

            // GET request
            $url = "https://www.usahas.com/webservices/Fluffy_AHAS_2023.asmx/GetAHASRisk2023_12?Type=MILAIR&Area=%27" . $icao . "%27&iMonth=" . $month . "&iDay=" . $day . "&iHour=" . $hour;
                        
            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'GET'
                ],
            ];
            
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            
            if ($result === false) {
                /* Handle error */
                echo "uh oh...";
            }

            // Filter unnecessary xml
            $pattern = "/<diffgr:diffgram[\S\s]*<\/diffgr:diffgram>/";
            
            if (preg_match_all($pattern, $result, $matches)) {
                return simplexml_load_string($matches[0][0]);
            } else {
                return null;
            }
        }

        function getNOTAMS($icao) {
            // POST Request
            $url = "https://www.notams.faa.gov/dinsQueryWeb/queryRetrievalMapAction.do";
            $data = ["reportType" => "Report", "retrieveLocId" => $icao, "actionType" => "notamRetrievalByICAOs", "submit" => "View NOTAMs"];
            
            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ],
            ];
            
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            
            if ($result === false) {
                /* Handle error */
                echo "uh oh...";
            }
            
            // remove newlines
            $pattern = "/\n/";
            $str = preg_replace($pattern, " ", $result);

            // Pull notams out of PRE tags
            $pattern = "/<PRE>(.*?)<\/PRE>/";
            
            if (preg_match_all($pattern, $str, $matches)) {
                foreach($matches[1] as &$value) {
                    echo "<div class=\"notam " . $icao . "_notam\" hidden>" . $value . "</div>";
                }
            } else {
                echo "no matches found";
            }
        }

        function getMETAR($icao) {
            $url = "https://aviationweather.gov/cgi-bin/data/metar.php?ids=" . $icao . "&hours=0&order=id%2C-obs&sep=true";
            
            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'GET'
                ],
            ];
            
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result === false) {
                /* Handle error */
                echo "uh oh...";
            }
            
            echo "<p class=\"metar " . $icao . "_metar\" hidden>" . $result . "</p>";
        }
        
        function getTAF($icao) {
            $url = "https://aviationweather.gov/cgi-bin/data/taf.php?ids=" . $icao . "&sep=true";
            
            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'GET'
                ],
            ];
            
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result === false) {
                /* Handle error */
                echo "uh oh...";
            }
            
            echo "<p class=\"taf " . $icao . "_taf\" hidden>" . $result . "</p>";
        }

        // Currently unused
        function GmtTimeToLocalTime($time) {
            date_default_timezone_set('UTC');
            $new_date = new DateTime($time);
            $new_date->setTimeZone(new DateTimeZone($_POST["timezone"]));
            // $t = strtotime($datetime);
            // $datetime = date('Hi',$t);
            return $new_date->format("Hi");
        }
    ?>
    <script>
        window.onload = function () {
            // Timezone
            document.getElementById('timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone;

            // NOTAM settings checkboxes
            document.getElementById("notam_hide_checkbox").addEventListener("click", toggleNOTAMHide);
            document.getElementById("notam_id_checkbox").addEventListener("click", toggleNOTAMID);
            document.getElementById("notam_valid_checkbox").addEventListener("click", toggleNOTAMValid);
            document.getElementById("notam_created_checkbox").addEventListener("click", toggleNOTAMCreated);
            
            // Functions to run on refresh
            rebuildNOTAMs();
            rebuildWeather();
            rebuildAHAS();
            // generateTable();

            toggleNOTAMHide();
            toggleNOTAMID();
            toggleNOTAMValid();
            toggleNOTAMCreated();
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
    </script>
</html>