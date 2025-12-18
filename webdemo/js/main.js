// 通用JavaScript函数

// 检查用户登录状态
function checkLoginStatus() {
    $.ajax({
        url: 'api/check-login.php',
        method: 'GET',
        success: function(response) {
            if (response && response.success && response.logged_in) {
                setNavbarAuthState(true);
            } else {
                setNavbarAuthState(false);
            }
        },
        error: function() {
            setNavbarAuthState(false);
        }
    });
}

function setNavbarAuthState(loggedIn) {
    const loginLinks = $('.navbar a[href="login.html"]');
    const logoutLink = $('#logoutBtn');

    if (loggedIn) {
        loginLinks.hide();
        logoutLink.show();
    } else {
        loginLinks.show();
        logoutLink.hide();
    }
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

// 页面加载时检查登录状态
$(document).ready(function() {
    // 统一绑定退出事件（适配各页面导航栏里写死的 #logoutBtn）
    $(document).off('click', '#logoutBtn').on('click', '#logoutBtn', function(e) {
        e.preventDefault();
        logout();
    });

    // 默认：未登录（先隐藏退出、显示登录），等 checkLoginStatus 返回后再切换
    setNavbarAuthState(false);

    // 所有页面都检查一次登录状态，用于统一切换“登录/退出”按钮
    checkLoginStatus();
});
