# ������������� ���������� ������ MySQL
FROM mysql:8.0
LABEL maintainer="admin@hh-soft.ru"

# ���������� ����������������� ��������
USER root

# ��������� vim


# ����������� � ��������� ������� �������������
COPY /files/init.sh /docker-entrypoint-initdb.d/
RUN chmod +x /docker-entrypoint-initdb.d/init.sh

# �������� ���������� ��� SQL ������
RUN mkdir /temp_sql
COPY /files/sql /temp_sql

# ������� � ������������� ������������
USER mysql
