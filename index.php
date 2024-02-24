<!DOCTYPE html>
<html lang="en">
    <head>
        <title>BWN</title>
        <link rel="icon" type="image/x-icon" href="https://hpanel.hostinger.com/favicons/hostinger.png">
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="Default page" name="description">
        <meta content="width=device-width, initial-scale=1" name="viewport">
    </head>
    <body style="margin:0 auto;">
        <form method="post" action="index.php" style="margin: 0 auto; width:250px; padding: 1rem;">
            <input type="text" name="icao" placeholder="ICAO, ICAO, ICAO...">
            <input type="submit" value="Enter" name="submit">
        </form>


        <br>
    </body>
    <?php
        if(isset($_POST['icao'])) {
            $str = $_POST['icao'];
            $pattern = "/([0-9A-Za-z]{4})/";
            
            if (preg_match_all($pattern, $str, $matches)) {
                echo "<table style=\"border: 1px solid black; border-collapse: collapse;\">";
                foreach($matches[1] as &$value) {
                    getAHAS($value);
                }
                echo "</table>";
                
                foreach($matches[1] as &$value) {
                    getMETAR($value);
                    getTAF($value);
                 }
                
                foreach($matches[1] as &$value) {
                     getNOTAMS($value);
                }
            }
        } 
        
        function getAHAS($icao) {
            // Read JSON file
            $json = file_get_contents('icao.json'); 
            $json_data = json_decode($json,true); 
            
            // Lookup icao full name
            $icao_full_name =  $json_data[strtoupper($icao)];
            
            // Replace utf
            $pattern = "/ /";
            $icao_full_name = preg_replace($pattern, "%20", $icao_full_name);
            
            // get uTC month, day, and hour
            $month = gmdate("n");
            $day = gmdate("j");
            $hour = gmdate("G");
            
            $url = "https://www.usahas.com/webservices/Fluffy_AHAS_2023.asmx/GetAHASRisk2023_12?Type=MILAIR&Area=%27" . $icao_full_name . "%27&iMonth=" . $month . "&iDay=" . $day . "&iHour=" . $hour;
            
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

            // remove excess xml and parse
            $pattern = "/<diffgr:diffgram[\S\s]*<\/diffgr:diffgram>/";
            if (preg_match_all($pattern, $result, $matches)) {
                $xml = simplexml_load_string($matches[0][0]);
                
                echo "<tr>";
                echo "<td style=\"border: 1px solid black; padding: 0.5rem\">" . strtoupper($icao) . "</td>";
                for ($i = 0; $i <= 11; $i++) {
                    if($i == 0) {
                        $risk = $xml->NewDataSet->Table->AHASRISK;
                    } else {
                        $table = "Table" . $i;
                        $risk = $xml->NewDataSet->$table->AHASRISK;
                    }
                    
                    if($risk == "MODERATE") {
                        $risk = "MOD";
                        $cell_color = "yellow";
                    } else if ($risk == "SEVERE") {
                        $risk = "SEV";
                        $cell_color = "red";
                    } else {
                        $cell_color = "green";
                    }
                    
                    echo "<td style=\"border: 1px solid black; padding: 0.5rem; background-color: " . $cell_color . ";\">" . $risk . "</td>";
                }
                echo "</tr>";
                
            } else {
                echo "no matches found";
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
            
            echo "<p>" . $result . "</p>";
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
            
            echo "<p>" . $result . "</p>";
        }
    ?>
</html>