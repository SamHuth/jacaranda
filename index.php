<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMDb API</title>

    <style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: sans-serif;
    }
    .container{
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
        padding: 50px 10px;
        
    }
    .welcome{
        margin-bottom: 20px;
        text-align: center;
    }
    #colourform fieldset{
        padding: 20px;
        margin: 10px;
        border-radius: 15px;
        border: 2px solid #3e3e3e;
        display: flex;
        justify-content: space-around;
        align-items: center;
        background: #fff;
    }
    #colourform legend{
        background: #3e3e3e;
        color: #fff;
        padding: 2px 5px;
        border-radius: 5px;
    }
    #colourform #submit{
        color: #3e3e3e;
        border: 2px solid #3e3e3e;
        background: #fff;
        border-radius: 4px;
        font-weight: bold;
        padding: 4px 10px;
        font-size: 16px;
    }
    #colourform #submit:hover, #colourform #submit:focus{
        background: #ccc;
    }
    #colourform label, #colourform input{
        cursor: pointer
    }
    .movie-list{
        display: flex;  
        flex-wrap: wrap;
    }
    .movie-card{
        display: flex;
        width: 100%;
        flex: 1 400px;
        padding: 20px;
        max-width: 400px;
        margin: 10px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 0 12px 4px rgba(0,0,0,0.1);
    }
    .movie-card .movie-details{
        padding: 10px;
    }
    .movie-card .movie-details h2{
        margin-bottom: 10px;
    }
    .movie-card .movie-details span{
        color: #fff;
        background: #3272e6;
        padding: 2px 5px;
        border-radius: 5px;
    }
    .movie-card .movie-details span.red{
        background: #e83e3e;
    }
    .movie-card .movie-details span.blue{
        background: #3272e6;
    }
    .movie-card .movie-details span.green{
        background: #51bb76;
    }
    .movie-card .movie-details span.yellow{
        background: #e4cc21;
    }
    .movie-card .movie-poster, .movie-card .movie-details{
        display: flex;
        width: 50%;
    }
    .movie-card .movie-poster img{
        width: 100%;
        border-radius: 5px;
    }
    </style>
</head>
<body>
    <div class="container">

        <h1 class="welcome">Welcome to the OMDB!</h1>
        <form id="colourform" method="get">
            <fieldset>
                <legend>Select a colour:</legend>
                <div class="form-item">
                    <input type="radio" id="red" name="colour" value="red" <?php if($_GET['colour'] == 'red' || $_GET['colour'] == ''){ echo 'checked'; } ?> >
                    <label for="red">Red</label>
                </div>
                <div class="form-item">
                    <input type="radio" id="blue" name="colour" value="blue" <?php if($_GET['colour'] == 'blue'){ echo 'checked'; } ?> >
                    <label for="blue">Blue</label>
                </div>
                <div class="form-item">
                    <input type="radio" id="green" name="colour" value="green" <?php if($_GET['colour'] == 'green'){ echo 'checked'; } ?> >
                    <label for="green">Green</label>
                </div>
                <div class="form-item">
                    <input type="radio" id="yellow" name="colour" value="yellow" <?php if($_GET['colour'] == 'yellow'){ echo 'checked'; } ?> >
                    <label for="yellow">Yellow</label>
                </div>
                <div class="form-item">
                    <input type="submit" id="submit" value="Search for colour">
                </div>
            </fieldset>
        </form>

        <div class="movie-list">
            <?php

            # SET accepted values
            $acceptedValues = ['red', 'blue', 'green', 'yellow'];
            $colour = $_GET['colour'];

            # IF a colour has been submitted and is one of the accepted values
            if($colour != '' && in_array($colour, $acceptedValues)){

                # DEFINE colour and api URL
                $url = "http://www.omdbapi.com/?s={$colour}&apikey=2da8b7c";

                # CALL api for results
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response_json = curl_exec($ch);
                curl_close($ch);
                $response=json_decode($response_json, true);

                # OUTPUT the results in html
                foreach($response['Search'] as $movie){

                    $title = $movie['Title'];
                    $poster = $movie['Poster'];
                    $year = $movie['Year'];

                    ## IDENTIFY and highlight the key colour in the title
                    if(strpos(strtoupper($title), strtoupper($colour)) !== false){

                        $titleBreakdown = explode(" ", $title);

                        for($i = 0; $i < count($titleBreakdown); $i++){

                            if(preg_match("/{$colour}/i", $titleBreakdown[$i])) {
                                $titleBreakdown[$i] = "<span class='{$colour}'>{$titleBreakdown[$i]}</span>";
                            }
                        }

                        $titleOutput = implode(" ", $titleBreakdown);
                    }

                    # RETURN the html output of the movie details
                    echo "
                        <div class='movie-card'>
                            <div class='movie-poster'>
                                <img src='{$poster}' alt='Movie poster for {$title}, {$year}' />
                            </div>
                            <div class='movie-details'>
                                <div>
                                    <h2>{$titleOutput}</h2>
                                    <p>{$year}</p>
                                </div>
                            </div>
                        </div>
                    ";
                }

            }
            ?>
        </div>

    </div>
</body>
</html>