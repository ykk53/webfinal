// 通用JavaScript函数

// 检查用户登录状态
function checkLoginStatus() {
    $.ajax({
        url: 'api/check-login.php',
        method: 'GET',
        success: function(response) {
            if (response && response.success && response.logged_in) {
                setNavbarAuthState(true, response.is_admin);
            } else {
                setNavbarAuthState(false, false);
            }
        },
        error: function() {
            setNavbarAuthState(false, false);
        }
    });
}

function setNavbarAuthState(loggedIn, isAdmin) {
    const loginLinks = $('.navbar a[href="login.html"]');
    const logoutLink = $('#logoutBtn');
    const userCenterLink = $('.navbar a[href="user-center.html"]');
    
    // 检查是否已存在管理后台链接
    let adminLink = $('#adminLink');

    if (loggedIn) {
        loginLinks.hide();
        logoutLink.show();
        userCenterLink.show();
        
        if (isAdmin) {
            if (adminLink.length === 0) {
                // 在退出按钮前插入管理后台链接
                $('<a href="manager.html" id="adminLink"><i class="fas fa-tachometer-alt"></i> 管理后台</a>').insertBefore(logoutLink);
            }
        } else {
            if (adminLink.length > 0) adminLink.remove();
        }
    } else {
        loginLinks.show();
        logoutLink.hide();
        userCenterLink.hide();
        if (adminLink.length > 0) adminLink.remove();
    }
}

// 更新导航栏
function updateNavbar(username, isAdmin) {
    const userActions = $('.user-actions');
    let html = `<span style="color: white; margin-right: 15px;">欢迎，${username}</span>`;
    
    if (isAdmin) {
        html += '<a href="manager.html">后台管理</a>';
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

// 安全地渲染富文本内容（用于详情页）
function renderRichText(elementId, text) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    if (!text || text.trim() === '') {
        element.innerHTML = '<p>暂无详细介绍</p>';
        return;
    }
    
    // 使用Markdown解析器
    element.innerHTML = MarkdownParser.parse(text);
}
// 渲染建筑卡片
function renderArchitectureCard(item) {
    // 处理图片路径，如果没有图片则使用占位图
    const imageUrl = item.image && item.image.trim() !== '' ? item.image : 'https://placehold.co/600x400/8f1414/ffffff?text=中国古建';

    // 使用Markdown解析器生成纯文本摘要
    const desc = item.description ? 
        MarkdownParser.generateExcerpt(item.description, 80) : 
        '暂无描述';
    
    const dynasty = item.dynasty || '未知朝代';
    const location = item.location || '未知地点';

    return `
        <div class="card">
                <img src="${imageUrl}" alt="${item.name}" class="card-image" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e0e0e0/333333?text=暂无图片';">
            <div class="card-content">
                <h3>${item.name}</h3>
                <p style="font-size: 0.9em; color: var(--brand); margin-bottom: 8px;">
                    <i class="fas fa-tag"></i> ${item.type} &nbsp; 
                    <i class="fas fa-landmark"></i> ${dynasty}
                </p>
                <p style="color: var(--muted); font-size: 0.9em; flex-grow: 1;">${desc}</p>
                <a href="detail.html?id=${item.id}" class="btn-link">
                    查看详情 <i class="fas fa-arrow-right" style="font-size: 0.8em; margin-left: 4px;"></i>
                </a>
            </div>
        </div>
    `;
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


