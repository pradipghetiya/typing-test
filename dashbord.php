<?php
// PHP code to handle any server-side logic (if needed in the future)
$pageTitle = "Enhanced Typing Test";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-color: #f4f4f8;
            --text-color: #333;
            --primary-color: #3498db;
            --secondary-color: #e2e2e2;
            --accent-color: #f39c12;
            --error-color: #e74c3c;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .dark-theme {
            --bg-color: #2c3e50;
            --text-color: #ecf0f1;
            --secondary-color: #34495e;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-items {
            display: flex;
            align-items: center;
        }

        .user-icon {
            position: relative;
            cursor: pointer;
            margin-left: 1rem;
        }

        .user-info {
            display: none;
            position: absolute;
            right: 0;
            background-color: var(--secondary-color);
            padding: 0.5rem;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .user-icon:hover .user-info {
            display: block;
        }

        main {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .settings {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        select {
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid var(--secondary-color);
            background-color: var(--bg-color);
            color: var(--text-color);
            font-size: 1rem;
        }

        .typing-area {
            background-color: var(--secondary-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .typing-area::before {
            content: "No copy-paste allowed";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background-color: var(--error-color);
            color: white;
            padding: 5px;
            font-size: 12px;
            display: none;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .typing-area.copy-paste-attempt::before {
            display: block;
        }

        .paragraph {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 1rem;
            white-space: pre-wrap;
        }

        textarea {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            background-color: var(--bg-color);
            color: var(--text-color);
            resize: none;
        }

        .wrong-input {
            background-color: rgba(231, 76, 60, 0.1);
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 1rem;
            background-color: var(--secondary-color);
            padding: 1rem;
            border-radius: 8px;
        }

        .stat {
            text-align: center;
        }

        .stat-label {
            font-weight: bold;
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            background-color: var(--primary-color);
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem;
        }

        button:hover {
            background-color: var(--accent-color);
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        #themeToggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            margin-right: 1rem;
        }

        #highScore {
            text-align: center;
            margin-bottom: 1rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        #topRaces {
            margin-top: 2rem;
            background-color: var(--secondary-color);
            padding: 1rem;
            border-radius: 8px;
        }

        #topRaces h3 {
            margin-top: 0;
            color: var(--primary-color);
        }

        #topRacesList {
            list-style-type: none;
            padding: 0;
        }

        #topRacesList li {
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background-color: rgba(255,255,255,0.1);
            border-radius: 4px;
        }

        #resultScreen {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
        }

        #resultContent {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 80%;
            max-height: 80%;
            overflow-y: auto;
        }

        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: 1rem auto;
        }

        #closeResult {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            cursor: pointer;
            background: none;
            border: none;
            color: var(--text-color);
        }
    </style>
</head>
<body class="light-theme">
    <nav>
        <div class="logo">TypingTest</div>
        <div class="nav-items">
            <button id="themeToggle">🌙</button>
            <div class="user-icon">👤
                <div class="user-info">
                    <p id="username">Username</p>
                    <p id="email">user@example.com</p>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div id="highScore"></div>

        <div class="settings">
            <select id="modeSelect">
                <option value="paragraph">Paragraph</option>
                <option value="word">Word</option>
                <option value="quote">Quote</option>
            </select>
            <select id="timeSelect">
                <option value="30">30 seconds</option>
                <option value="60" selected>1 minute</option>
                <option value="120">2 minutes</option>
                <option value="300">5 minutes</option>
            </select>
            <select id="difficultySelect">
                <option value="easy">Easy</option>
                <option value="medium" selected>Medium</option>
                <option value="hard">Hard</option>
                <option value="expert">Expert</option>
            </select>
        </div>

        <div class="typing-area">
            <div id="paragraph" class="paragraph"></div>
            <textarea id="userInput" rows="5" placeholder="Start typing here..." autocomplete="off" spellcheck="false"></textarea>
        </div>

        <div class="stats">
            <div class="stat">
                <span class="stat-label">WPM:</span>
                <span id="wpm">0</span>
            </div>
            <div class="stat">
                <span class="stat-label">KPM:</span>
                <span id="kpm">0</span>
            </div>
            <div class="stat">
                <span class="stat-label">Accuracy:</span>
                <span id="accuracy">100%</span>
            </div>
            <div class="stat">
                <span class="stat-label">Time:</span>
                <span id="time">0s</span>
            </div>
        </div>

        <div class="buttons">
            <button id="startBtn">Start</button>
            <button id="restartBtn">Restart</button>
            <button id="multiplayerBtn">Multiplayer Mode</button>
        </div>

        <div id="topRaces">
            <h3>Top Races</h3>
            <ul id="topRacesList"></ul>
        </div>
    </main>

    <div id="resultScreen">
        <div id="resultContent">
            <button id="closeResult">&times;</button>
            <h2>Test Results</h2>
            <div class="chart-container">
                <canvas id="wpmChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="accuracyChart"></canvas>
            </div>
            <div id="resultStats"></div>
        </div>
    </div>

    <script>
        const paragraph = document.getElementById('paragraph');
        const userInput = document.getElementById('userInput');
        const startBtn = document.getElementById('startBtn');
        const restartBtn = document.getElementById('restartBtn');
        const wpmDisplay = document.getElementById('wpm');
        const kpmDisplay = document.getElementById('kpm');
        const accuracyDisplay = document.getElementById('accuracy');
        const timeDisplay = document.getElementById('time');
        const themeToggle = document.getElementById('themeToggle');
        const timeSelect = document.getElementById('timeSelect');
        const difficultySelect = document.getElementById('difficultySelect');
        const modeSelect = document.getElementById('modeSelect');
        const mainContent = document.querySelector('main');
        const highScoreDisplay = document.getElementById('highScore');
        const multiplayerBtn = document.getElementById('multiplayerBtn');
        const topRacesList = document.getElementById('topRacesList');
        const resultScreen = document.getElementById('resultScreen');
        const resultContent = document.getElementById('resultContent');
        const closeResult = document.getElementById('closeResult');
        const resultStats = document.getElementById('resultStats');

        let startTime, endTime, timeLimit, intervalId;
        let totalTyped = 0, mistakes = 0, currentIndex = 0;
        let isTestActive = false;
        let highScores = JSON.parse(localStorage.getItem('highScores')) || {};
        let mistypedWords = JSON.parse(localStorage.getItem('mistypedWords')) || {};
        let topRaces = JSON.parse(localStorage.getItem('topRaces')) || [];
        let currentMode = 'paragraph';
        let wpmData = [];
        let accuracyData = [];

        // Dark/Light mode setup
        const body = document.body;
        const darkModeClass = 'dark-theme';

        function toggleTheme() {
            body.classList.toggle(darkModeClass);
            const isDarkMode = body.classList.contains(darkModeClass);
            localStorage.setItem('isDarkMode', isDarkMode);
            themeToggle.textContent = isDarkMode ? '☀️' : '🌙';
        }

        // Check local storage for theme preference
        const isDarkMode = localStorage.getItem('isDarkMode') === 'true';
        if (isDarkMode) {
            toggleTheme();
        }

        themeToggle.addEventListener('click', toggleTheme);

        const easyParagraphs = [
            "The quick brown fox jumps over the lazy dog.",
            "A journey of a thousand miles begins with a single step.",
            "To be or not to be, that is the question."
        ];

        const mediumParagraphs = [
            "Success is not final, failure is not fatal: it is the courage to continue that counts.",
            "I have a dream that one day this nation will rise up and live out the true meaning of its creed.",
            "Ask not what your country can do for you – ask what you can do for your country."
        ];

        const hardParagraphs = [
            "It was the best of times, it was the worst of times, it was the age of wisdom, it was the age of foolishness.",
            "Two roads diverged in a wood, and I - I took the one less traveled by, and that has made all the difference.",
            "I must not fear. Fear is the mind-killer. Fear is the little-death that brings total obliteration."
        ];

        const expertParagraphs = [
            "The quantum superposition of states in a multi-particle system gives rise to entanglement, a phenomenon that defies classical intuition.",
            "The intricate interplay between syntax and semantics in natural language processing poses significant challenges for machine learning algorithms.",
            "The emergence of blockchain technology has revolutionized decentralized systems, offering new paradigms for trust and consensus in digital transactions."
        ];

        const words = [
            "the", "be", "to", "of", "and", "a", "in", "that", "have", "I",
            "it", "for", "not", "on", "with", "he", "as", "you", "do", "at"
        ];

        const quotes = [
            "The only way to  do great work is to love what you do.",
            "Life is what happens when you're busy making other plans.",
            "Strive not to be a success, but rather to be of value."
        ];

        function getRandomParagraph() {
            const difficulty = difficultySelect.value;
            let paragraphs;
            switch (difficulty) {
                case 'easy':
                    paragraphs = easyParagraphs;
                    break;
                case 'medium':
                    paragraphs = mediumParagraphs;
                    break;
                case 'hard':
                    paragraphs = hardParagraphs;
                    break;
                case 'expert':
                    paragraphs = expertParagraphs;
                    break;
                default:
                    paragraphs = mediumParagraphs;
            }
            return paragraphs[Math.floor(Math.random() * paragraphs.length)];
        }

        function getRandomWords(count) {
            return Array.from({ length: count }, () => words[Math.floor(Math.random() * words.length)]).join(' ');
        }

        function getRandomQuote() {
            return quotes[Math.floor(Math.random() * quotes.length)];
        }

        function startTest() {
            if (isTestActive) return;
            isTestActive = true;
            userInput.value = '';
            userInput.disabled = false;
            userInput.focus();
            startBtn.disabled = true;
            
            currentMode = modeSelect.value;
            switch (currentMode) {
                case 'word':
                    paragraph.textContent = getRandomWords(20);
                    break;
                case 'quote':
                    paragraph.textContent = getRandomQuote();
                    break;
                default:
                    paragraph.textContent = getRandomParagraph();
            }
            
            timeLimit = parseInt(timeSelect.value);
            startTime = new Date().getTime();
            totalTyped = 0;
            mistakes = 0;
            currentIndex = 0;
            wpmData = [];
            accuracyData = [];
            updateTimer();
            intervalId = setInterval(updateTimer, 1000);
        }

        function restartTest() {
            clearInterval(intervalId);
            isTestActive = false;
            userInput.value = '';
            userInput.disabled = false;
            startBtn.disabled = false;
            wpmDisplay.textContent = '0';
            kpmDisplay.textContent = '0';
            accuracyDisplay.textContent = '100%';
            timeDisplay.textContent = '0s';
            paragraph.textContent = 'Click "Start" to begin a new test.';
            mainContent.style.display = 'block';
            resultScreen.style.display = 'none';
            mistakes = 0;
            currentIndex = 0;
            totalTyped = 0;
        }

        function endTest() {
            clearInterval(intervalId);
            isTestActive = false;
            userInput.disabled = true;
            startBtn.disabled = false;
            endTime = new Date().getTime();
            const timeElapsed = (endTime - startTime) / 1000; // in seconds
            const wpm = Math.round((totalTyped / 5) / (timeElapsed / 60));
            const kpm = Math.round(totalTyped / (timeElapsed / 60));
            const accuracy = totalTyped > 0 ? Math.max(0, Math.round(((totalTyped - mistakes) / totalTyped) * 100)) : 100;
            updateHighScore(wpm, accuracy);
            updateTopRaces(wpm, kpm, accuracy, timeElapsed);
            showResults(wpm, kpm, accuracy, timeElapsed);
        }

        function updateTimer() {
            const currentTime = new Date().getTime();
            const elapsedTime = Math.floor((currentTime - startTime) / 1000);
            const remainingTime = timeLimit - elapsedTime;
            if (remainingTime <= 0) {
                endTest();
            } else {
                timeDisplay.textContent = `${remainingTime}s`;
                // Update WPM and KPM in real-time
                const words = userInput.value.trim().split(/\s+/).length;
                const characters = userInput.value.length;
                const minutes = elapsedTime / 60;
                const wpm = Math.round(words / minutes) || 0;
                const kpm = Math.round(characters / minutes) || 0;
                const accuracy = totalTyped > 0 ? Math.max(0, Math.round(((totalTyped - mistakes) / totalTyped) * 100)) : 100;
                wpmDisplay.textContent = wpm;
                kpmDisplay.textContent = kpm;
                wpmData.push(wpm);
                accuracyData.push(accuracy);
            }
        }

        function checkAccuracy() {
            const typedText = userInput.value;
            const originalText = paragraph.textContent;
            
            // Check for new mistakes
            for (let i = currentIndex; i < typedText.length; i++) {
                if (typedText[i] !== originalText[i]) {
                    mistakes++;
                    updateMistypedWords(originalText[i]);
                    // Break on first mistake to avoid counting multiple mistakes for the same character
                    break;
                }
            }
            
            currentIndex = typedText.length;
            totalTyped = Math.max(totalTyped, currentIndex);
            
            // Calculate accuracy
            const accuracy = totalTyped > 0 ? Math.max(0, Math.round(((totalTyped - mistakes) / totalTyped) * 100)) : 100;
            accuracyDisplay.textContent = `${accuracy}%`;
            
            if (typedText.length === originalText.length) {
                endTest();
            }
        }

        function updateMistypedWords(character) {
            mistypedWords[character] = (mistypedWords[character] || 0) + 1;
            localStorage.setItem('mistypedWords', JSON.stringify(mistypedWords));
        }

        function updateHighScore(wpm, accuracy) {
            const currentHighScore = highScores[currentMode] || { wpm: 0, accuracy: 0 };
            if (wpm > currentHighScore.wpm || (wpm === currentHighScore.wpm && accuracy > currentHighScore.accuracy)) {
                highScores[currentMode] = { wpm, accuracy };
                localStorage.setItem('highScores', JSON.stringify(highScores));
                displayHighScore();
            }
        }

        function displayHighScore() {
            const highScore = highScores[currentMode] || { wpm: 0, accuracy: 0 };
            highScoreDisplay.textContent = `High Score: ${highScore.wpm} WPM (${highScore.accuracy}% accuracy)`;
        }

        function updateTopRaces(wpm, kpm, accuracy, timeElapsed) {
            topRaces.push({ wpm, kpm, accuracy, timeElapsed, date: new Date().toISOString() });
            topRaces.sort((a, b) => b.wpm - a.wpm);
            topRaces = topRaces.slice(0, 5); // Keep only top 5 races
            localStorage.setItem('topRaces', JSON.stringify(topRaces));
            displayTopRaces();
        }

        function displayTopRaces() {
            topRacesList.innerHTML = '';
            topRaces.forEach((race, index) => {
                const li = document.createElement('li');
                li.textContent = `#${index + 1}: ${race.wpm} WPM, ${race.kpm} KPM, ${race.accuracy}% accuracy (${new Date(race.date).toLocaleDateString()})`;
                topRacesList.appendChild(li);
            });
        }

        function showResults(wpm, kpm, accuracy, timeElapsed) {
            resultScreen.style.display = 'block';
            resultStats.innerHTML = `
                <p>Words per Minute: ${wpm}</p>
                <p>Keystrokes per Minute: ${kpm}</p>
                <p>Accuracy: ${accuracy}%</p>
                <p>Mistakes: ${mistakes}</p>
                <p>Time Elapsed: ${timeElapsed.toFixed(2)} seconds</p>
                <p>Error Rate: ${(mistakes / totalTyped * 100).toFixed(2)}%</p>
            `;
            
            createWPMChart();
            createAccuracyChart();
        }

        function createWPMChart() {
            const ctx = document.getElementById('wpmChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array.from({length: wpmData.length}, (_, i) => i + 1),
                    datasets: [{
                        label: 'WPM over time',
                        data: wpmData,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function createAccuracyChart() {
            const ctx = document.getElementById('accuracyChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array.from({length: accuracyData.length}, (_, i) => i + 1),
                    datasets: [{
                        label: 'Accuracy over time',
                        data: accuracyData,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }

        function preventCopyPaste(e) {
            e.preventDefault();
            document.querySelector('.typing-area').classList.add('copy-paste-attempt');
            setTimeout(() => {
                document.querySelector('.typing-area').classList.remove('copy-paste-attempt');
            }, 2000);
        }

        function checkInput(e) {
            const typedText = e.target.value;
            const originalText = paragraph.textContent;
            
            if (typedText[typedText.length - 1] !== originalText[typedText.length - 1]) {
                e.target.classList.add('wrong-input');
            } else {
                e.target.classList.remove('wrong-input');
            }
            
            checkAccuracy();
        }

        userInput.addEventListener('input', checkInput);
        userInput.addEventListener('copy', preventCopyPaste);
        userInput.addEventListener('paste', preventCopyPaste);
        userInput.addEventListener('cut', preventCopyPaste);
        startBtn.addEventListener('click', startTest);
        restartBtn.addEventListener('click', restartTest);
        closeResult.addEventListener('click', () => {
            resultScreen.style.display = 'none';
        });

        // Existing event listener for preventing console opening
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) {
                e.preventDefault();
                if (isTestActive) restartTest();
            }
        });

        // Add a new event listener to monitor for the console opening
        let consoleOpen = false;
        setInterval(() => {
            if (consoleOpen) {
                restartTest();
                consoleOpen = false; // Reset the flag after restarting the test
            }
        }, 1000); // Check every second

        // Override the console object to detect console access
        const originalConsoleLog = console.log;
        console.log = function (...args) {
            consoleOpen = true;
            originalConsoleLog.apply(console, args);
        };

        // Initialize
        displayHighScore();
        displayTopRaces();
        restartTest();
    </script>
</body>
</html>