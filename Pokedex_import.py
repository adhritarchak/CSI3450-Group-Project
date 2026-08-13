import pymysql, json

DATABASE_NAME = "Pokemon_Database"
SPECIES_TABLE_NAME = "Poke_Species"

def create_pkmn_database(print_info: bool = False) -> dict:
    data: dict
    with open('newPokedex.json', 'r') as file:
        data = json.load(file)
    if print_info:
        for keys in data:
            item = data[keys]
            print(f"{int(item['dexNum']):3d}: {item['name']}")
    return data

def main():
    conn = pymysql.connect(
        host="127.0.0.1",
        user="student",
        password="csi3450",
        database=DATABASE_NAME,
        charset="utf8mb4"
    )
    conn.autocommit = False

    with conn.cursor() as cursor:
        try:
            data = list()

            cursor.execute("""
            INSERT INTO %s (%s, %s)
            """, SPECIES_TABLE_NAME, data)
        except Exception as e:
            conn.rollback()
            print(f"ROLLBACK  ✗  Error during process: {e}")

    if conn is not None:
        conn.close()

if __name__ == '__main__':
    create_pkmn_database(True)