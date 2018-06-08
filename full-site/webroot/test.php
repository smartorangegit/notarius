<?$a = 'test';?>
<!DOCTYPE html>
<html>
<head>
    <title>Google Calendar API Quickstart</title>
    <meta charset='utf-8' />
</head>
<body>

<button id="send_event">Send</button>

<script type="text/javascript">
    // Client ID and API key from the Developer Console
    var CLIENT_ID = '777080281097-vjo87rsuqa0sf6p7bfiu8m8t5qmq7hoh.apps.googleusercontent.com';
    var API_KEY = 'AIzaSyCAIvPJBfcjK9KofUiSPmH0ig-QrWCNvlQ';
    // Array of API discovery doc URLs for APIs used by the quickstart
    var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
    // Authorization scopes required by the API; multiple scopes can be
    // included, separated by spaces.
    var SCOPES = "https://www.googleapis.com/auth/calendar";

    /**
     *  On load, called to load the auth2 library and API client library.
     */
    function handleClientLoad() {
        gapi.load('client:auth2', initClient);
    }

    /**
     *  Initializes the API client library and sets up sign-in state
     *  listeners.
     */
    function initClient() {
        gapi.client.init({
            apiKey: API_KEY,
            clientId: CLIENT_ID,
            discoveryDocs: DISCOVERY_DOCS,
            scope: SCOPES
        }).then(function () {});
    }

    var test = {
        eventName: '<?php echo $a;?>',
        location: 'My house location',
        description: 'My description',
        start: {
            dateTime: '<?echo $data['date'];?>T<?echo $data['time'];?>'
        },
        end: {
            dateTime: '<?echo $data['date'];?>T<?echo $data['time'];?>'
        }
    }

    function sendCustomEvent(data) {
        var event = {
            'summary': data.eventName,
            'location': data.location,
            'description': data.description,
            'start': {
                'dateTime': data.start.dateTime,
                'timeZone': 'Europe/Kiev'
            },
            'end': {
                'dateTime':  data.end.dateTime,
                'timeZone':  'Europe/Kiev'
            },
            'reminders': {
                'useDefault': false,
                'overrides': [
                    {'method': 'email', 'minutes': 24 * 60},
                    {'method': 'popup', 'minutes': 10}
                ]
            }
        };

        var request = gapi.client.calendar.events.insert({
            'calendarId': 'primary',
            'resource': event
        });

        request.execute(function(event) {
            //console.log(event)
        });

    }

//    document.getElementById('send_event').addEventListener('click', function() {
//        sendCustomEvent(test)
//    });
    setTimeout(function(){
        sendCustomEvent(test);
    }, 2000);
</script>
<script async defer src="https://apis.google.com/js/api.js"
        onload="this.onload=function(){};handleClientLoad()"
        onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
</body>
</html>