import pymysql

def main():
    conn = pymysql.connect(
        host="127.0.0.1",
        user="student",
        password="csi3450",
        database="Pokemon_Database",
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor
    )
    with conn.cursor() as cursor:
        pass

if __name__ == '__main__':
    main()