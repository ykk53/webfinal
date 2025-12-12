-- 创建数据库
CREATE DATABASE IF NOT EXISTS ancient_architecture DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE ancient_architecture;

-- 用户表
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 建筑表
CREATE TABLE IF NOT EXISTS architectures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('民宿', '官府', '皇宫', '桥梁') NOT NULL,
    dynasty VARCHAR(50),
    location VARCHAR(100),
    image VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 知识问答表
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_answer ENUM('A', 'B', 'C', 'D') NOT NULL,
    explanation TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 答题记录表
CREATE TABLE IF NOT EXISTS quiz_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    user_answer ENUM('A', 'B', 'C', 'D') NOT NULL,
    is_correct TINYINT(1) NOT NULL,
    answered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 插入示例数据
-- 插入管理员账号 (密码: admin123)
INSERT INTO users (username, password, email, is_admin) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 1);

-- 插入示例建筑数据
INSERT INTO architectures (name, type, dynasty, location, image, description) VALUES
('故宫', '皇宫', '明清', '北京', 'images/gugong.jpg', '故宫是中国明清两代的皇家宫殿，旧称紫禁城，位于北京中轴线的中心。'),
('天坛', '官府', '明清', '北京', 'images/tiantan.jpg', '天坛是明清两代皇帝祭天、祈谷和祈雨的场所。'),
('赵州桥', '桥梁', '隋', '河北赵县', 'images/zhaozhou.jpg', '赵州桥是世界上现存年代久远、跨度最大的单孔坦弧敞肩石拱桥。'),
('徽州民居', '民宿', '明清', '安徽', 'images/huizhou.jpg', '徽州民居是中国传统民居建筑的重要流派之一。');

-- 插入示例问答题
INSERT INTO quizzes (question, option_a, option_b, option_c, option_d, correct_answer, explanation) VALUES
('故宫始建于哪个朝代？', '唐朝', '宋朝', '明朝', '清朝', 'C', '故宫始建于明朝永乐年间。'),
('赵州桥建于哪个朝代？', '汉朝', '隋朝', '唐朝', '宋朝', 'B', '赵州桥建于隋朝，是世界上现存最古老的石拱桥之一。'),
('天坛主要用于什么活动？', '居住', '祭祀', '防御', '娱乐', 'B', '天坛是明清两代皇帝祭天、祈谷的场所。');
