//Remove function for delete
function remove(id, mode, text) {
    mode = mode || "?mode=delete&id=" + id;
    text = text || "Vil du slette denne record?";
    if(confirm(text)) {
        document.location.href = mode;
    }
}