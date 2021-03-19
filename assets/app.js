
/**
 * global function to delete a message
 * 
 * @param  id 
 */
function globalDeleteMessage(id, callback) {
    if(confirm("Are you sure?")) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if(callback) {
                    callback();
                }
            }
        };
        xhttp.open("POST", "ajax.php", true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        var params = "cmd=deleteMessage&id=" + id;
        xhttp.send(params);
    }
} 
