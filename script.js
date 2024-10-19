const paragraph = document.getElementById('paragraph');
const userInput = document.getElementById('userInput');
const startBtn = document.getElementById('startBtn');
const restartBtn = document.getElementById('restartBtn');
const wpmDisplay = document.getElementById('wpm');
const accuracyDisplay = document.getElementById('accuracy');
const timeDisplay = document.getElementById('time');
const themeToggle = document.getElementById('themeToggle');
const timeSelect = document.getElementById('timeSelect');
const difficultySelect = document.getElementById('difficultySelect');
const modeSelect = document.getElementById('modeSelect');
const mainContent = document.querySelector('main');
const resultScreen = document.createElement('div');
const highScoreDisplay = document.getElementById('highScore');
const practiceBtn = document.getElementById('practiceBtn');
const multiplayerBtn = document.getElementById('multiplayerBtn');

let startTime, endTime, timeLimit, intervalId;
let totalTyped = 0, mistakes = 0, currentIndex = 0;
let isTestActive = false;
let highScores = JSON.parse(localStorage.getItem('highScores')) || {};
let mistypedWords = JSON.parse(localStorage.getItem('mistypedWords')) || {};
let currentMode = 'paragraph';

// Dark/Light mode setup
const body = document.body;
const darkModeClass = 'dark-mode';

function toggleTheme() {
    body.classList.toggle(darkModeClass);
    const isDarkMode = body.classList.contains(darkModeClass);
    localStorage.setItem('isDarkMode', isDarkMode);
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

const words = [
    "the", "be", "to", "of", "and", "a", "in", "that", "have", "I",
    "it", "for", "not", "on", "with", "he", "as", "you", "do", "at"
];

const quotes = [
    "The only way to do great work is to love what you do.",
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
    const accuracy = totalTyped > 0 ? Math.max(0, Math.round(((totalTyped - mistakes) / totalTyped) * 100)) : 100;
    updateHighScore(wpm, accuracy);
    showResults(wpm, accuracy, timeElapsed);
}

function updateTimer() {
    const currentTime = new Date().getTime();
    const elapsedTime = Math.floor((currentTime - startTime) / 1000);
    const remainingTime = timeLimit - elapsedTime;
    if (remainingTime <= 0) {
        endTest();
    } else {
        timeDisplay.textContent = `${remainingTime}s`;
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

function showResults(wpm, accuracy, timeElapsed) {
    mainContent.style.display = 'none';
    resultScreen.style.display = 'block';
    resultScreen.innerHTML = 
        `<h2>Test Results</h2>
        <p>Words per Minute: ${wpm}</p>
        <p>Accuracy: ${accuracy}%</p>
        <p>Mistakes: ${mistakes}</p>
        <p>Time Elapsed: ${timeElapsed.toFixed(2)} seconds</p>
        <p>Error Rate: ${(mistakes / totalTyped * 100).toFixed(2)}%</p>
        <button id="newTestBtn">Start New Test</button>
        <button id="practiceBtn">Practice Mistyped Words</button>
    `;
    document.body.appendChild(resultScreen);
    document.getElementById('newTestBtn').addEventListener('click', restartTest);
    document.getElementById('practiceBtn').addEventListener('click', startPracticeMode);
}

function startPracticeMode() {
    const sortedMistypes = Object.entries(mistypedWords).sort((a, b) => b[1] - a[1]);
    const practiceText = sortedMistypes.map(word => `${word[0]} (${word[1]} times)`).join(' ');
    paragraph.textContent = practiceText || "No mistakes yet!";
    userInput.value = '';
    userInput.disabled = false;
    userInput.focus();
    timeDisplay.textContent = 'Infinite';
}

userInput.addEventListener('input', checkAccuracy);
startBtn.addEventListener('click', startTest);
restartBtn.addEventListener('click', restartTest);

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
restartTest();
