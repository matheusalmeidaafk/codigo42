-- Execute uma única vez no mesmo banco usado pelo Código 42.
ALTER TABLE produto_variacao
    ADD COLUMN estoque INT NOT NULL DEFAULT 0;
