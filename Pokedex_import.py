import pymysql, json

DATABASE_NAME = "Pokemon_Database"
SPECIES_TABLE_NAME = "Poke_Species"

def create_pkmn_database(print_info: bool = False) -> dict:
    """Loads the newPokedex.json file from memory and returns its data as a dict."""
    data: dict      # Declaration of data object
    with open('newPokedex.json', 'r') as file:
        data = json.load(file)      # Load json file into dict

    if print_info:                  # prints database info into console (for debugging)
        for keys in data:
            item = data[keys]
            print(f"{int(item['dexNum']):4d}: {item['name']}")
    return data

def main():
    # Connect to mysql server
    conn = pymysql.connect(
        host="127.0.0.1",
        user="student",
        password="csi3450",
        database=DATABASE_NAME,
        charset="utf8mb4"
    )

    # Prevents mysql from autocommiting so we can rollback in case of an error
    conn.autocommit = False

    with conn.cursor() as cursor:
        try:
            data = list()
            # TODO: Formatting data into something we can put in the sql query

            cursor.execute("""
            INSERT INTO %s (%s, %s)
            VALUES (%s, %s)
            """, SPECIES_TABLE_NAME, data)
        except Exception as e:
            # If we get an error, undo all work so as to not screw up anything in the database
            conn.rollback()
            print(f"ROLLBACK  ✗  Error during process: {e}")

    if conn is not None: # Close connection
        conn.close()

# --- Remember to change this to main() when ready to execute ---
if __name__ == '__main__':
    create_pkmn_database(True)