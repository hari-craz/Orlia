<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Alert</title>
</head>
<body>
    <h1>JavaScript Alert Test</h1>
    
    <button onclick="testBasicAlert()">Test 1: Basic Alert</button>
    <button onclick="testJQuery()">Test 2: jQuery</button>
    <button onclick="testAjax()">Test 3: AJAX Test</button>
    
    <div id="result" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;"></div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    
    <script>
        function testBasicAlert() {
            alert('✓ Basic JavaScript Alert Works!');
        }
        
        function testJQuery() {
            if (typeof jQuery !== 'undefined') {
                $('#result').html('<p style="color:green;"><b>✓ jQuery is loaded!</b></p>');
                alert('✓ jQuery is working!');
            } else {
                alert('✗ jQuery is NOT loaded!');
            }
        }
        
        function testAjax() {
            $.ajax({
                url: 'backend.php',
                method: 'POST',
                data: {
                    Add_newuser: 'true',
                    fullName: 'Test',
                    rollNumber: 'TEST123',
                    year: 'I year',
                    mailid: 'test@test.com',
                    phoneNumber: '9999999999',
                    department: 'CSE',
                    daySelection: 'day1',
                    events: 'Tamilspeech'
                },
                success: function(response) {
                    console.log('Response:', response);
                    alert('✓ AJAX Success!\n\nResponse:\n' + response);
                    $('#result').html('<p style="color:green;"><b>✓ AJAX Request Successful!</b><br>Response: ' + response + '</p>');
                },
                error: function(xhr, status, error) {
                    alert('✗ AJAX Error: ' + error);
                    $('#result').html('<p style="color:red;"><b>✗ AJAX Error:</b> ' + error + '</p>');
                }
            });
        }
        
        console.log('Page loaded. jQuery version:', typeof jQuery !== 'undefined' ? jQuery.fn.jquery : 'NOT LOADED');
    </script>
</body>
</html>
