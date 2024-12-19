
-- Créer la table des utilisateurs
CREATE TABLE users (
                       id UUID PRIMARY KEY,
                       email VARCHAR(255) UNIQUE NOT NULL,
                       password TEXT NOT NULL,
                       roles TEXT[] NOT NULL DEFAULT ARRAY['ROLE_USER'],
                       created_at TIMESTAMP DEFAULT NOW()
);

-- Créer la table des clients
CREATE TABLE clients (
                         id UUID PRIMARY KEY,
                         user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                         name TEXT NOT NULL,
                         email TEXT,
                         phone TEXT,
                         address TEXT,
                         created_at TIMESTAMP DEFAULT NOW()
);

-- Créer la table des produits/prestations
CREATE TABLE products (
                          id UUID PRIMARY KEY,
                          user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                          name TEXT NOT NULL,
                          description TEXT,
                          price NUMERIC(10, 2),
                          created_at TIMESTAMP DEFAULT NOW()
);

-- Créer la table des ventes
CREATE TABLE sales (
                       id UUID PRIMARY KEY,
                       user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                       client_id UUID REFERENCES clients(id) ON DELETE SET NULL,
                       product_id UUID REFERENCES products(id) ON DELETE SET NULL,
                       amount NUMERIC(10, 2),
                       sale_date TIMESTAMP DEFAULT NOW()
);

-- Créer la table des campagnes de mail
CREATE TABLE email_campaigns (
                                 id UUID PRIMARY KEY,
                                 user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                                 name TEXT NOT NULL,
                                 subject TEXT,
                                 body TEXT,
                                 scheduled_at TIMESTAMP,
                                 created_at TIMESTAMP DEFAULT NOW()
);
