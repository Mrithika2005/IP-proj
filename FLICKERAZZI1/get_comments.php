function loadComments(movieId) {
    const commentsList = document.getElementById('commentsList');
    commentsList.innerHTML = "";

    fetch(`get_comments.php?movie_id=${movieId}`)
    .then(response => response.json())
    .then(comments => {
        comments.forEach(comment => {
            const commentDiv = document.createElement('div');
            commentDiv.className = 'comment';
            commentDiv.textContent = comment.comment_text;
            commentsList.appendChild(commentDiv);
        });
    });
}
