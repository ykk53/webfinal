// 通用JavaScript函数

// 检查用户登录状态
function checkLoginStatus() {
    $.ajax({
        url: 'api/check-login.php',
        method: 'GET',
        success: function(response) {
            if (response.success && response.logged_in) {
                updateNavbar(response.username, response.is_admin);
            }
        }
    });
}

// 更新导航栏
function updateNavbar(username, isAdmin) {
    const userActions = $('.user-actions');
    let html = `<span style="color: white; margin-right: 15px;">欢迎，${username}</span>`;
    
    if (isAdmin) {
        html += '<a href="admin.html">后台管理</a>';
    }
    
    html += '<a href="user-center.html">个人中心</a>';
    html += '<a href="#" id="logoutBtn">退出</a>';
    
    userActions.html(html);
    
    // 绑定退出事件
    $('#logoutBtn').on('click', function(e) {
        e.preventDefault();
        logout();
    });
}

// 退出登录
function logout() {
    $.ajax({
        url: 'api/logout.php',
        method: 'POST',
        success: function() {
            window.location.href = 'index.html';
        }
    });
}

// 格式化日期
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('zh-CN');
}

// 显示消息
function showMessage(element, message, type) {
    const className = type === 'success' ? 'success' : 'error';
    $(element).html(`<div class="message ${className}">${message}</div>`);
}

// 页面加载时检查登录状态
$(document).ready(function() {
    // 某些页面需要检查登录状态
    const currentPage = window.location.pathname.split('/').pop();
    const publicPages = ['index.html', 'login.html', 'register.html', 'architecture-list.html', 'architecture-detail.html'];
    
    if (!publicPages.includes(currentPage) && currentPage !== '') {
        checkLoginStatus();
    }
});
