<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <footer class="footer">
        <button onclick="window.print();">Print <i class='bx bx-printer'></i></button>
        <p id="footer-text">© 2024 Clymex. All rights reserved.</p>
        <button onclick="resizeText('increase')">Increase Text Size</button>
        <button onclick="resizeText('decrease')">Decrease Text Size</button>
        <button onclick="resetTextSize()">Reset Text Size</button>
    </footer>

    <script>
        
        document.addEventListener("DOMContentLoaded", function() {
            function resizeText(action) {
                const textElements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, li, a, span, div');

                textElements.forEach(element => {
                    let currentSize = window.getComputedStyle(element, null).getPropertyValue('font-size');
                    currentSize = parseFloat(currentSize);

                    if (action === 'increase' && currentSize < 24) { // Set max font size
                        currentSize += 2;
                    } else if (action === 'decrease' && currentSize > 12) { // Set min font size
                        currentSize -= 2;
                    }

                    element.style.fontSize = currentSize + 'px';
                });
            }

            function resetTextSize() {
                const textElements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, li, a, span, div');
                textElements.forEach(element => {
                    element.style.fontSize = '';
                });
            }

            // Assign the resizeText and resetTextSize functions to the buttons
            document.querySelector("button[onclick*='increase']").onclick = function() { resizeText('increase'); };
            document.querySelector("button[onclick*='decrease']").onclick = function() { resizeText('decrease'); };
            document.querySelector("button[onclick*='resetTextSize']").onclick = resetTextSize;
        });
    </script>
</body>
</html>
