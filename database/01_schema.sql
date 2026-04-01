SET NAMES utf8mb4;
SET time_zone = '+00:00';

DROP TABLE IF EXISTS contains_;
DROP TABLE IF EXISTS require_;
DROP TABLE IF EXISTS company_evaluation;
DROP TABLE IF EXISTS wishlist;
DROP TABLE IF EXISTS application;
DROP TABLE IF EXISTS user_;
DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS skill;
DROP TABLE IF EXISTS offer;
DROP TABLE IF EXISTS company;

CREATE TABLE company (
    id_company INT AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    address VARCHAR(150),
    city VARCHAR(100),
    country VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    PRIMARY KEY (id_company)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE role (
    id_role INT AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE skill (
    id_skill INT AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    PRIMARY KEY (id_skill)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_ (
    id_user INT AUTO_INCREMENT,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    id_user_1 INT NULL,
    id_role INT NOT NULL,
    PRIMARY KEY (id_user),
    UNIQUE KEY uq_user_email (email),
    CONSTRAINT fk_user_supervisor
        FOREIGN KEY (id_user_1) REFERENCES user_(id_user)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    CONSTRAINT fk_user_role
        FOREIGN KEY (id_role) REFERENCES role(id_role)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE offer (
    id_offer INT AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    salary DECIMAL(15,2),
    publication_date DATE,
    duration VARCHAR(50),
    status VARCHAR(50),
    id_company INT NOT NULL,
    PRIMARY KEY (id_offer),
    CONSTRAINT fk_offer_company
        FOREIGN KEY (id_company) REFERENCES company(id_company)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE application (
    id_app INT AUTO_INCREMENT,
    cv VARCHAR(255),
    motivation_letter TEXT,
    app_date DATE,
    status VARCHAR(50),
    id_offer INT NOT NULL,
    id_user INT NOT NULL,
    PRIMARY KEY (id_app),
    CONSTRAINT fk_application_offer
        FOREIGN KEY (id_offer) REFERENCES offer(id_offer)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_application_user
        FOREIGN KEY (id_user) REFERENCES user_(id_user)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE wishlist (
    id_wishlist INT AUTO_INCREMENT,
    added_date DATE,
    id_user INT NOT NULL,
    PRIMARY KEY (id_wishlist),
    UNIQUE KEY uq_wishlist_user (id_user),
    CONSTRAINT fk_wishlist_user
        FOREIGN KEY (id_user) REFERENCES user_(id_user)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE company_evaluation (
    id_evaluation INT AUTO_INCREMENT,
    rating INT,
    comment TEXT,
    evaluation_date DATE,
    id_company INT NOT NULL,
    id_user INT NOT NULL,
    PRIMARY KEY (id_evaluation),
    CONSTRAINT fk_company_evaluation_company
        FOREIGN KEY (id_company) REFERENCES company(id_company)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_company_evaluation_user
        FOREIGN KEY (id_user) REFERENCES user_(id_user)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE require_ (
    id_offer INT NOT NULL,
    id_skill INT NOT NULL,
    PRIMARY KEY (id_offer, id_skill),
    CONSTRAINT fk_require_offer
        FOREIGN KEY (id_offer) REFERENCES offer(id_offer)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_require_skill
        FOREIGN KEY (id_skill) REFERENCES skill(id_skill)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contains_ (
    id_offer INT NOT NULL,
    id_wishlist INT NOT NULL,
    PRIMARY KEY (id_offer, id_wishlist),
    CONSTRAINT fk_contains_offer
        FOREIGN KEY (id_offer) REFERENCES offer(id_offer)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_contains_wishlist
        FOREIGN KEY (id_wishlist) REFERENCES wishlist(id_wishlist)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
