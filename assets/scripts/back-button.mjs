document.querySelectorAll(".back-button").forEach((button) => {
	button.addEventListener("click", () => {
		history.back();
	});
});
