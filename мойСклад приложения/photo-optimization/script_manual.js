function toggleContent(contentClass) {
	const content = document.querySelector(`.${contentClass}`); // Исправлено
	content.classList.toggle('show'); // Переключаем класс
}