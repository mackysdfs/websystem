// Handle modal functionality
const modal = document.getElementById("transactionModal");
const openModalBtn = document.getElementById("openTransactionModal");
const closeBtn = document.querySelector(".close-btn");

openModalBtn.onclick = () => modal.style.display = "flex";
closeBtn.onclick = () => modal.style.display = "none";

window.onclick = (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

// Handle form submission
document.getElementById("transactionForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    
    const equipment = document.getElementById("equipment").value;
    const borrowDate = document.getElementById("borrowDate").value;
    const returnDate = document.getElementById("returnDate").value;

    try {
        const response = await fetch("api/submit_request.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ equipment, borrowDate, returnDate })
        });

        const data = await response.json();

        if (response.ok) {
            alert("Request submitted successfully!");
            modal.style.display = "none";
            location.reload(); // Refresh to show new request
        } else {
            throw new Error(data.error || "Failed to submit request");
        }
    } catch (error) {
        alert(error.message);
    }
});

// Set minimum dates for date inputs
const today = new Date().toISOString().split('T')[0];
document.getElementById("borrowDate").min = today;
document.getElementById("returnDate").min = today;

// Update return date min when borrow date changes
document.getElementById("borrowDate").addEventListener("change", (e) => {
    document.getElementById("returnDate").min = e.target.value;
});
