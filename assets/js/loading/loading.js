document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('preloader');
    const content = document.querySelector('.container');

    if (!loader || !content) {
        console.error('Không tìm thấy phần tử preloader hoặc container');
        return;
    }

    content.style.visibility = 'hidden';
    loader.style.display = 'flex';

    window.addEventListener('load', function () {
        loader.style.display = 'none';
        content.style.visibility = 'visible';
    });
});