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
    <body style="margin:0 auto;">
        <form method="post" action="index.php" style="margin: 0 auto; width:250px; padding: 1rem;">
            <input type="text" name="icao" placeholder="ICAO, ICAO, ICAO...">
            <input type="submit" value="Enter" name="submit" onclick="setTimeZone()">
        </form>
        <br>
    </body>
    <?php
        // Get ICAO from POST variables
        if(isset($_POST['icao'])) {
            $str = $_POST['icao'];
            $pattern = "/([0-9A-Za-z]{4})/";
            
            // Look for 4-digit/character sequences
            if (preg_match_all($pattern, $str, $matches)) {
                
                // AHAS table
                // getAHAS($matches[1]);
                
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
            echo "<table style=\"border-collapse: collapse;\">";
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
                    echo "<td style=\"border: 1px solid black; padding: 0.5rem\"></td>";
                    
                    // DateTime row
                    for($table_index = 0; $table_index <= 11; $table_index++) {
                        if($table_index == 0) {
                            $datetime = $xml->NewDataSet->Table->DateTime;
                        } else {
                            $table = "Table" . $table_index;
                            $datetime = $xml->NewDataSet->$table->DateTime;
                        }

                        $t = strtotime($datetime);
                        $datetime = date('Hi',$t);

                        // echo "<td style=\"border: 1px solid black; padding: 0.5rem\">" . GmtTimeToLocalTime($datetime) . "</td>";
                        echo "<td style=\"border: 1px solid black; padding: 0.5rem\">" . $datetime . "</td>";
                    }
                    echo "</tr>";

                    // AHAS risk for first in the list
                    echo "<tr>";
                    echo "<td style=\"border: 1px solid black; padding: 0.5rem\">" . strtoupper($icao_list[$icao]) . "</td>";
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

                        echo "<td style=\"border: 1px solid black; padding: 0.5rem; background-color: " . $cell_color . ";\">" . $ahas_risk . "</td>";
                    }
                    echo "</tr>";
                } else {
                    // AHAS risk for the rest of the list
                    echo "<tr>";
                    echo "<td style=\"border: 1px solid black; padding: 0.5rem\">" . strtoupper($icao_list[$icao]) . "</td>";
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

                        echo "<td style=\"border: 1px solid black; padding: 0.5rem; background-color: " . $cell_color . ";\">" . $ahas_risk . "</td>";
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
            
            echo "<p>" . strtoupper($icao) . " NOTAMs:</p>";
            if (preg_match_all($pattern, $str, $matches)) {
                foreach($matches[1] as &$value) {
                    echo "<div>" . $value . "</div>";
                }
            } else {
                echo "no matches found";
            }

            // echo gettype($result);
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
            
            echo "<p class=\"metar\">" . $result . "</p>";
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
            
            echo "<p class=\"taf\">" . $result . "</p>";
        }

        // Currently unused
        function GmtTimeToLocalTime($time) {
            date_default_timezone_set('UTC');
            $new_date = new DateTime($time);
            $new_date->setTimeZone(new DateTimeZone($_GET["timezone"]));
            return $new_date->format("Y-m-d h:i:s");
        }
    ?>
    <script>
        window.onload = function () {
            // formatMETARs();
            formatTAFs();
        }

        function formatMETARs() {
            const metars = document.getElementsByClassName("metar");
            
            for (let i = 0; i < metars.length; i++) {
                console.log(metars[i].innerText);
            }
        }

        function formatTAFs() {
            const tafs = document.getElementsByClassName("taf");
            
            for (let i = 0; i < tafs.length; i++) {
                console.log(tafs[i].innerText);
            }
        }

        function setTimeZone() {
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams(url.search);
            const new_url = window.location.href + "?timezone=" + Intl.DateTimeFormat().resolvedOptions().timeZone;
            window.location.assign(new_url); 

            // if(!searchParams.has("timezone")) {
            //     const new_url = window.location.href + "?timezone=" + Intl.DateTimeFormat().resolvedOptions().timeZone;
            //     window.location.assign(new_url); 
            // }
        }

    </script>
</html>