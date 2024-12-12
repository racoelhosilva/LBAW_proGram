const toggleFAQ = () => {
    const faqArticles = document.querySelectorAll(".faq-container");

    faqArticles.forEach((article) => {
        const answer = article.querySelector(".answer");
        const arrowUp = article.querySelector(".arrow-up-icon-container");
        const arrowDown = article.querySelector(".arrow-down-icon-container");

        article.addEventListener("click", () => {
            const isOpen = answer.classList.contains("max-h-96");
            answer.classList.toggle("max-h-0", isOpen);
            answer.classList.toggle("max-h-96", !isOpen);
            answer.classList.toggle("mt-4");
            arrowUp.classList.toggle("hidden", !isOpen);
            arrowDown.classList.toggle("hidden", isOpen);
        });
    });
};

toggleFAQ();
