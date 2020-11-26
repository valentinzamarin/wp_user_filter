document.querySelectorAll('.page-link').forEach(btn => btn.addEventListener('click', filterUsers));
document.querySelector('#roles').addEventListener('change', filterUsers);
document.querySelector('#names').addEventListener('change', filterUsers);


document.querySelector('.page-item').classList.add('active'); // просто добавляем активный класс к первой странице

function filterUsers(event) {
    event.preventDefault();

    document.querySelectorAll('.page-item').forEach(item => item.classList.remove('active')); // убираем активные классы со всех остальных страниц
    let setPage;
    if (this.classList.contains('page-link')) {
        setPage = this.textContent;
    } else {
        setPage = 1; // при фильтрации селектами, перебрасываем на первую страницу
    }

    const userTable = document.querySelector('#table-response');
    const pagination = document.querySelector('.pagination');
    let currentUserRole = document.querySelector('#roles option:checked').value;
    let currentUserOrder = document.querySelector('#names option:checked').value;

    data = new FormData();
    data.append('action', 'user_filter');
    data.append('nonce', users_filter.nonce);
    data.append('set_page', setPage);
    data.append('role', currentUserRole);
    data.append('order', currentUserOrder);


    document.querySelector('#table-response').style.opacity = '0.5';
    const url = users_filter.ajax_url;
    fetch(url, {
            method: 'post',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            userTable.innerHTML = data.table;
            pagination.innerHTML = data.pagination;
            document.querySelectorAll('.page-link').forEach(btn => btn.addEventListener('click', filterUsers));
            document.querySelectorAll('.page-link').forEach(item => {
                if (item.textContent == setPage) {
                    item.parentElement.classList.add('active'); //добавляем активный класс к li текушей страницы
                }
            })
        })
        .finally(() => {
            document.querySelector('#table-response').style.opacity = '1';
        });
}