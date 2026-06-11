function bukaModalProfil() {
    document.getElementById('modalEditProfil').style.display = 'flex';
}

function tutupModalProfil() {
    document.getElementById('modalEditProfil').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('modalEditProfil');
    
    if (event.target === modal) {
        modal.style.display = "none";
    }
}