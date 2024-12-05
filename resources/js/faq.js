const toggleFAQ = () => {
    const faqArticles = document.querySelectorAll(".faq-container");

    faqArticles.forEach((article) => {
        const answer = article.querySelector(".answer");
        const arrowUp = article.querySelector(".arrow-up-icon-container");
        const arrowDown = article.querySelector(".arrow-down-icon-container");
        article.addEventListener("click", () => {
            answer.classList.toggle("hidden");
            arrowUp.classList.toggle("hidden");
            arrowDown.classList.toggle("hidden");
        });
    });
}
toggleFAQ();