<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
    <head>
        <title>BWN</title>
        <link rel="stylesheet" href="custom.css">
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="Default page" name="description">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
        <link href="fontawesome/css/brands.css" rel="stylesheet" />
        <link href="fontawesome/css/solid.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Nav bar -->
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="https://www.uptbwn.com">BWN</a>
                <form class="d-flex" role="search" method="post" action="index.php" style="margin: 1rem; margin: auto; width: fit-content;">
                    <input class="form-control me-2" type="search" aria-label="Search" name="icao" placeholder="ICAO, ICAO, ICAO..." style="text-transform:uppercase">
                    <input type="text" id="timezone" name="timezone" hidden>
                    <button class="btn btn-outline-success" type="submit" name="submit">Search</button>
                </form>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title fs-1" id="offcanvasNavbarLabel">Options</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body ps-4">
                        <span class="navbar-text fs-3">Appearance</span>
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <!-- <a class="nav-link active" aria-current="page" id="btnSwitch">Dark mode</a> -->
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="hide" id="btnSwitch" checked>
                                    <label class="form-check-label fs-4" for="btnSwitch">Dark Mode</label>
                                </div>
                            </li>
                        </ul>
                        <span class="navbar-text fs-3">NOTAM Settings</span>
                        <ul class="navbar-nav justify-content-end flex-grow-1">
                            <li>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="hide" id="notam_hide_checkbox" checked>
                                    <label class="form-check-label fs-4" for="notam_hide_checkbox">Hide/Show</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="id" id="notam_id_checkbox" checked>
                                    <label class="form-check-label fs-4" for="notam_id_checkbox">Show IDs</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="valid" id="notam_valid_checkbox" checked>
                                    <label class="form-check-label fs-4" for="notam_valid_checkbox">Show Valid Period</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="created" id="notam_created_checkbox" checked>
                                    <label class="form-check-label fs-4" for="notam_created_checkbox">Show Date Created</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hidden example container mt-4 pb-2 -->
        <div class="container text-center bg-dark-subtle border rounded" id="ICAO_card" hidden>
            <div class="row">
                <div class="col border-bottom editable fs-2" id="ICAO_title">ZZZZ - ZZZZ</div>
            </div>
            <div class="row">
                <div class="col-sm-1 border-bottom text-center align-middle pe-2" style="display: flex; align-items: center; flex-wrap: wrap;"><strong>AHAS</strong></div>
                <div class="col-sm-11">
                    <div class="row">
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_1">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_2">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_3">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_4">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_5">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_6">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_7">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_8">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_9">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_10">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_11">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_time_12">ZZZZ</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_1">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_2">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_3">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_4">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_5">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_6">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_7">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_8">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_9">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_10">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_11">ZZZZ</div>
                        <div class="col-sm-1 border-bottom border-start p-0 editable" id="ICAO_ahas_risk_12">ZZZZ</div>
                    </div>
                </div>
            </div>
            <div class="row text-start">
                <div class="col border-bottom fs-5" style="width: 100%"><strong>METAR</strong></div>
            </div>
                <div class="row text-start">
                    <div class="col border-bottom editable ps-5" id="ICAO_metar">ZZZZ</div>
                </div>
                <div class="row text-start">
                    <div class="col border-bottom fs-5"><strong>TAF</strong></div>
                </div>
                <div class="row text-start">
                    <div class="col border-bottom editable ps-5" id="ICAO_taf">ZZZZ</div>
                </div>
                <div class="row text-start">
                    <div class="col border-bottom fs-5"><strong>NOTAMs</strong></div>
                </div>
                <div class="row text-start">
                    <div class="col border-bottom">
                        <div class="editable pt-2" id="ICAO_notams"></div>
                    </div>
                </div>

                <!-- <p class="d-inline-flex gap-1">
                    <a class="btn btn-primary editable" data-bs-toggle="collapse" href="#ICAOcollapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Hidden NOTAMs
                    </a>
                </p>
                    <div class="collapse editable" id="ICAOcollapseExample">
                    <div class="card card-body">
                        Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
                    </div>
                </div> -->

                <div class="accordion mt-2" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Hidden NOTAMs
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="editable text-start" id="ICAO_hidden_notams"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Hidden API data elements -->
        <div id="birds"></div>
        <div id="weather"></div>
        <div id="notams">
            <!-- Settings for configuring notams -->
            <div id="notam_settings" hidden>
                <input type="checkbox" name="hide" id="notam_hide_checkbox" checked>
                <label for="hide"> Hide</label>
                <input type="checkbox" name="id" id="notam_id_checkbox">
                <label for="id"> IDs</label>
                <input type="checkbox" name="valid" id="notam_valid_checkbox" checked>
                <label for="valid"> Valid</label>
                <input type="checkbox" name="created" id="notam_created_checkbox">
                <label for="created"> Created</label>
            </div>
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
            echo "<table id=\"ahas_table\" style=\"border-collapse: collapse;\" hidden>";
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

                        echo "<td style=\"border: 1px solid black;\" id=\"time_" . ($table_index + 1) . "\">" . GmtTimeToLocalTime($datetime) . "</td>";
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
                        } else if ($ahas_risk == "LOW"){
                            $cell_color = "green";
                        }

                        echo "<td style=\"border: 1px solid black; background-color: " . $cell_color . ";\" id=\"" . $icao_list[$icao] . "_risk_" . ($table_index + 1) . "\">" . $ahas_risk . "</td>";
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

                        echo "<td style=\"border: 1px solid black; background-color: " . $cell_color . ";\" id=\"" . $icao_list[$icao] . "_risk_" . ($table_index + 1) . "\">" . $ahas_risk . "</td>";
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
    <script type="text/javascript" src="icao.json"></script>
    <script src="bwn.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>