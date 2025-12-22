// markdown-parser.js - 简单的Markdown解析器
class MarkdownParser {
    static parse(text) {
        if (!text || text.trim() === '') return '<p>暂无内容</p>';
        
        // 转义HTML
        text = this.escapeHtml(text);
        
        // 处理各种Markdown语法（新增图片解析，放在链接解析之前）
        text = this.processHeaders(text);
        text = this.processBoldItalic(text);
        text = this.processCode(text);
        text = this.processLists(text);
        text = this.processImages(text); // 新增：解析图片
        text = this.processLinks(text);
        text = this.processParagraphs(text);
        
        return text;
    }
    
    static escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    
    static processHeaders(text) {
        // 处理三级标题
        text = text.replace(/^### (.+)$/gm, '<h3>$1</h3>');
        // 处理二级标题
        text = text.replace(/^## (.+)$/gm, '<h2>$1</h2>');
        // 处理一级标题
        text = text.replace(/^# (.+)$/gm, '<h1>$1</h1>');
        
        return text;
    }
    
    static processBoldItalic(text) {
        // 处理粗体
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // 处理斜体
        text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        return text;
    }
    
    static processCode(text) {
        // 处理行内代码
        return text.replace(/`(.*?)`/g, '<code>$1</code>');
    }
    
    static processLists(text) {
        let lines = text.split('\n');
        let result = [];
        let inList = false;
        let listType = '';
        let listItems = [];
        
        for (let i = 0; i < lines.length; i++) {
            let line = lines[i].trim();
            
            // 检测无序列表项
            if (line.match(/^[-*]\s+(.+)/)) {
                if (!inList) {
                    inList = true;
                    listType = 'ul';
                } else if (listType !== 'ul') {
                    // 结束上一个列表
                    result.push(this.wrapList(listItems, listType));
                    listItems = [];
                    listType = 'ul';
                }
                listItems.push(line.replace(/^[-*]\s+/, ''));
            }
            // 检测有序列表项
            else if (line.match(/^\d+\.\s+(.+)/)) {
                if (!inList) {
                    inList = true;
                    listType = 'ol';
                } else if (listType !== 'ol') {
                    // 结束上一个列表
                    result.push(this.wrapList(listItems, listType));
                    listItems = [];
                    listType = 'ol';
                }
                listItems.push(line.replace(/^\d+\.\s+/, ''));
            }
            // 非列表项
            else {
                if (inList) {
                    result.push(this.wrapList(listItems, listType));
                    listItems = [];
                    inList = false;
                    listType = '';
                }
                result.push(line);
            }
        }
        
        // 处理最后一个列表
        if (inList) {
            result.push(this.wrapList(listItems, listType));
        }
        
        return result.join('\n');
    }
    
    static wrapList(items, type) {
        if (items.length === 0) return '';
        
        const listItems = items.map(item => `<li>${item}</li>`).join('');
        return `<${type}>${listItems}</${type}>`;
    }
// 修改 markdown-parser.js 中的 processImages 方法
static processImages(text) {
    const imgRegex = /!\[(.*?)\]\((.*?)(\s+"(.*?)")?\)/g;
    let processed = text.replace(imgRegex, (match, alt, url, titleGroup, title) => {
        const titleAttr = title ? `title="${title}"` : '';
        // 为每个图片容器添加唯一标识类，方便后续处理
        return `<div class="image-container single-img">
            <img src="${url}" alt="${alt}" ${titleAttr} class="description-image">
            ${alt ? `<p class="image-caption">${alt}</p>` : ''}
        </div>`;
    });

    // 改进的画廊分组逻辑：只将连续且中间没有其他内容的图片容器分组
    processed = processed.replace(
        /(<div class="image-container single-img">[\s\S]*?<\/div>)\s+(?=<div class="image-container single-img">)/g,
        '$1' // 移除连续图片容器之间的空白，使其能够被正确识别为一组
    );
    
    // 将连续的图片容器包裹到画廊容器中
    processed = processed.replace(
        /(<div class="image-container single-img">[\s\S]*?<\/div>)+/g,
        (match) => `<div class="image-gallery">${match}</div>`
    );

    return processed;
}
    static processLinks(text) {
        // 处理链接 [文本](URL)
        return text.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');
    }
    
    static processLinks(text) {
        // 处理链接 [文本](URL)
        return text.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');
    }
    
 // 修改 markdown-parser.js 中的 processParagraphs 方法
static processParagraphs(text) {
    let paragraphs = text.split(/\n\s*\n/);
    let result = [];
    
    for (let paragraph of paragraphs) {
        paragraph = paragraph.trim();
        if (paragraph) {
            // 完善排除条件，确保图片画廊和文字正确分离
            if (paragraph.startsWith('<div class="image-container') || 
                paragraph.startsWith('<div class="image-gallery') ||
                paragraph.startsWith('<ul>') || paragraph.startsWith('<ol>') || 
                paragraph.startsWith('<h1>') || paragraph.startsWith('<h2>') || 
                paragraph.startsWith('<h3>')) {
                result.push(paragraph);
            } else {
                paragraph = paragraph.replace(/\n/g, '<br>');
                result.push(`<p>${paragraph}</p>`);
            }
        }
    }
    
    return result.join('\n');
}
    
    // 生成纯文本摘要（用于列表页）
    static generateExcerpt(text, maxLength = 100) {
        if (!text) return '';
        
        // 移除Markdown标记（新增：移除图片标记）
        let plainText = text
            .replace(/#{1,3}\s+/g, '') // 移除标题标记
            .replace(/\*\*(.*?)\*\*/g, '$1') // 移除粗体
            .replace(/\*(.*?)\*/g, '$1') // 移除斜体
            .replace(/`(.*?)`/g, '$1') // 移除代码
            .replace(/!\[(.*?)\]\(.*?\)/g, '$1') // 移除图片标记，保留alt文本
            .replace(/\[(.*?)\]\(.*?\)/g, '$1') // 移除链接，保留文本
            .replace(/^[-*]\s+/gm, '') // 移除列表标记
            .replace(/^\d+\.\s+/gm, '') // 移除有序列表标记
            .replace(/\n+/g, ' ') // 换行转空格
            .trim();
        
        // 截断文本
        if (plainText.length > maxLength) {
            plainText = plainText.substring(0, maxLength) + '...';
        }
        
        return plainText;
    }
}