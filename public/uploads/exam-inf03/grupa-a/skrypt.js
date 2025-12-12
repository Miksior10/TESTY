const messageForm = document.getElementById('message-form');
const messageInput = document.getElementById('message-input');
const chatWindow = document.getElementById('chat-window');
const randomResponseButton = document.getElementById('random-response');

const krzysztofResponses = [
    'Świetnie!',
    'Kto gra główną rolę?',
    'Lubisz filmy Tego reżysera?',
    'Będę 10 minut wcześniej',
    'Może kupimy sobie popcorn?',
    'Ja wolę Colę',
    'Zaproszę jeszcze Grześka',
    'Tydzień temu też byłem w kinie na Diunie',
    'Ja funduję bilety'
];

messageForm.addEventListener('submit', function (event) {
    event.preventDefault();
    sendMessage();
});

randomResponseButton.addEventListener('click', generateRandomResponse);

function sendMessage() {
    const userMessage = messageInput.value.trim();
    if (!userMessage) {
        return;
    }
    appendMessage('jolanta', userMessage);
    messageInput.value = '';
    messageInput.focus();
}

function generateRandomResponse() {
    const randomIndex = Math.floor(Math.random() * krzysztofResponses.length);
    const response = krzysztofResponses[randomIndex];
    appendMessage('krzysztof', response);
}

function appendMessage(author, text) {
    const article = document.createElement('article');
    article.classList.add('message');

    const paragraph = document.createElement('p');
    paragraph.textContent = text;

    const image = document.createElement('img');

    if (author === 'jolanta') {
        article.classList.add('message-jolanta');
        image.src = 'Jolka.jpg';
        image.alt = 'Jolanta Nowak';
        article.appendChild(image);
        article.appendChild(paragraph);
    } else {
        article.classList.add('message-krzysztof');
        image.src = 'Krzysiek.jpg';
        image.alt = 'Krzysztof Łukasiński';
        article.appendChild(paragraph);
        article.appendChild(image);
    }

    chatWindow.appendChild(article);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}
