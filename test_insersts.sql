USE Pokemon_Database;


/* ============================================================
   1. REGIONS
   ============================================================ */

INSERT INTO Region
    (region_id, region_name, region_gen)
VALUES
    (1, 'Kanto', 1),
    (2, 'Johto', 2),
    (3, 'Hoenn', 3);


/* ============================================================
   2. LEVELING RATES
   ============================================================ */

INSERT INTO Leveling_Rate
    (LR_id, rate_name)
VALUES
    (1, 'Medium Slow'),
    (2, 'Medium Fast'),
    (3, 'Fast'),
    (4, 'Slow');


/* ============================================================
   3. EGG GROUPS
   ============================================================ */

INSERT INTO Egg_Group
    (egg_id, egg_name)
VALUES
    (1, 'Monster'),
    (2, 'Grass'),
    (3, 'Bug'),
    (4, 'Flying'),
    (5, 'Field'),
    (6, 'Water 1'),
    (7, 'Dragon'),
    (8, 'Human-Like');


/* ============================================================
   4. ABILITIES
   ============================================================ */

INSERT INTO Ability
    (ability_id, ability_name, ability_desc)
VALUES
    (1, 'Overgrow',
        'Powers up Grass-type moves when the Pokemon is in trouble.'),

    (2, 'Chlorophyll',
        'Boosts the Pokemon''s Speed in sunshine.'),

    (3, 'Blaze',
        'Powers up Fire-type moves when the Pokemon is in trouble.'),

    (4, 'Solar Power',
        'Boosts Special Attack in sunshine but lowers HP each turn.'),

    (5, 'Torrent',
        'Powers up Water-type moves when the Pokemon is in trouble.'),

    (6, 'Rain Dish',
        'The Pokemon gradually regains HP during rain.'),

    (7, 'Static',
        'Contact with the Pokemon may cause paralysis.'),

    (8, 'Lightning Rod',
        'Draws Electric-type moves to the Pokemon.'),

    (9, 'Intimidate',
        'Lowers the opposing Pokemon''s Attack when entering battle.'),

    (10, 'Keen Eye',
        'Prevents the Pokemon''s accuracy from being lowered.');


/* ============================================================
   5. TYPES
   ============================================================ */

INSERT INTO `Type`
    (type_id, type_name)
VALUES
    (1, 'Normal'),
    (2, 'Fire'),
    (3, 'Water'),
    (4, 'Electric'),
    (5, 'Grass'),
    (6, 'Ice'),
    (7, 'Fighting'),
    (8, 'Poison'),
    (9, 'Ground'),
    (10, 'Flying'),
    (11, 'Psychic'),
    (12, 'Bug'),
    (13, 'Rock'),
    (14, 'Ghost'),
    (15, 'Dragon');


/* ============================================================
   6. ITEMS
   ============================================================ */

INSERT INTO Item
    (item_id, item_name, item_desc)
VALUES
    (1, 'Potion',
        'Restores a small amount of HP.'),

    (2, 'Super Potion',
        'Restores more HP than a Potion.'),

    (3, 'Pokeball',
        'A device used for catching Pokemon.'),

    (4, 'Rare Candy',
        'Raises a Pokemon''s level.'),

    (5, 'Leftovers',
        'Restores a small amount of HP during battle.'),

    (6, 'Charcoal',
        'An item that strengthens Fire-type moves.'),

    (7, 'Mystic Water',
        'An item that strengthens Water-type moves.'),

    (8, 'Miracle Seed',
        'An item that strengthens Grass-type moves.'),

    (9, 'Oran Berry',
        'Restores a small amount of HP during battle.'),

    (10, 'Quick Claw',
        'May allow the holder to move before the opponent.');


/* ============================================================
   7. POKEMON SPECIES
   ============================================================ */

INSERT INTO Pokemon_Species
(
    species_id,
    dex_num,
    species_name,
    base_species,
    forme,
    gender,
    gender_ratio,
    not_fully_evolved,
    weight_kg,
    LR_id,
    base_HP,
    base_Atk,
    base_Def,
    base_SpA,
    base_SpD,
    base_Spe,
    pkmn_tag,
    pkmn_color
)
VALUES

/* Bulbasaur */
(
    1,
    1,
    'Bulbasaur',
    'Bulbasaur',
    'Normal',
    NULL,
    87.5,
    TRUE,
    6.9,
    1,
    45,
    49,
    49,
    65,
    65,
    45,
    'Seed Pokemon',
    'Green'
),

/* Ivysaur */
(
    2,
    2,
    'Ivysaur',
    'Bulbasaur',
    'Normal',
    NULL,
    87.5,
    TRUE,
    13.0,
    1,
    60,
    62,
    63,
    80,
    80,
    60,
    'Seed Pokemon',
    'Green'
),

/* Charmander */
(
    3,
    4,
    'Charmander',
    'Charmander',
    'Normal',
    NULL,
    87.5,
    TRUE,
    8.5,
    1,
    39,
    52,
    43,
    60,
    50,
    65,
    'Lizard Pokemon',
    'Red'
),

/* Charmeleon */
(
    4,
    5,
    'Charmeleon',
    'Charmander',
    'Normal',
    NULL,
    87.5,
    TRUE,
    19.0,
    1,
    58,
    64,
    58,
    80,
    65,
    80,
    'Flame Pokemon',
    'Red'
),

/* Squirtle */
(
    5,
    7,
    'Squirtle',
    'Squirtle',
    'Normal',
    NULL,
    87.5,
    TRUE,
    9.0,
    1,
    44,
    48,
    65,
    50,
    64,
    43,
    'Tiny Turtle Pokemon',
    'Blue'
),

/* Pikachu */
(
    6,
    25,
    'Pikachu',
    'Pikachu',
    'Normal',
    NULL,
    50.0,
    TRUE,
    6.0,
    2,
    35,
    55,
    40,
    50,
    50,
    90,
    'Mouse Pokemon',
    'Yellow'
),

/* Raichu */
(
    7,
    26,
    'Raichu',
    'Pikachu',
    'Normal',
    NULL,
    50.0,
    FALSE,
    30.0,
    2,
    60,
    90,
    55,
    90,
    80,
    110,
    'Mouse Pokemon',
    'Yellow'
),

/* Pidgey */
(
    8,
    16,
    'Pidgey',
    'Pidgey',
    'Normal',
    NULL,
    50.0,
    TRUE,
    1.8,
    2,
    40,
    45,
    40,
    35,
    35,
    56,
    'Tiny Bird Pokemon',
    'Brown'
);


/* ============================================================
   8. LOCATIONS
   ============================================================ */

INSERT INTO Location
    (location_id, region_id, location_name)
VALUES
    (1, 1, 'Pallet Town'),
    (2, 1, 'Viridian Forest'),
    (3, 1, 'Pewter City'),
    (4, 1, 'Route 1'),
    (5, 2, 'New Bark Town'),
    (6, 2, 'Violet City'),
    (7, 3, 'Littleroot Town'),
    (8, 3, 'Petalburg Woods');


/* ============================================================
   9. MOVES
   ============================================================ */

INSERT INTO `Move`
(
    move_id,
    type_id,
    move_name,
    move_bp,
    move_category,
    move_pp,
    move_accuracy,
    move_effect,
    move_desc
)
VALUES

(
    1,
    1,
    'Tackle',
    40,
    'Physical',
    35,
    100,
    'Deals normal physical damage.',
    'The user charges into the target.'
),

(
    2,
    5,
    'Vine Whip',
    45,
    'Physical',
    25,
    100,
    'Deals Grass-type physical damage.',
    'The user strikes the target with slender vines.'
),

(
    3,
    5,
    'Razor Leaf',
    55,
    'Physical',
    25,
    95,
    'Has an increased critical-hit ratio.',
    'Sharp leaves are launched at the target.'
),

(
    4,
    2,
    'Ember',
    40,
    'Special',
    25,
    100,
    'May burn the target.',
    'The target is attacked with small flames.'
),

(
    5,
    2,
    'Flamethrower',
    90,
    'Special',
    15,
    100,
    'May burn the target.',
    'The target is scorched with intense flames.'
),

(
    6,
    3,
    'Water Gun',
    40,
    'Special',
    25,
    100,
    'Deals Water-type special damage.',
    'The target is blasted with water.'
),

(
    7,
    4,
    'Thunder Shock',
    40,
    'Special',
    30,
    100,
    'May paralyze the target.',
    'The target is struck by an electric shock.'
),

(
    8,
    1,
    'Quick Attack',
    40,
    'Physical',
    30,
    100,
    'Usually strikes first.',
    'The user lunges at the target with great speed.'
),

(
    9,
    10,
    'Gust',
    40,
    'Special',
    35,
    100,
    'Deals Flying-type damage.',
    'The target is hit by a gust of wind.'
),

(
    10,
    12,
    'Bug Bite',
    60,
    'Physical',
    20,
    100,
    'May consume the target''s held berry.',
    'The user bites the target with sharp jaws.'
);


/* ============================================================
   10. TRAINERS
   ============================================================ */

INSERT INTO Trainer
    (trainer_id, location_id, trainer_name)
VALUES
    (1, 1, 'Professor Oak'),
    (2, 4, 'Youngster Joey'),
    (3, 3, 'Brock'),
    (4, 6, 'Falkner'),
    (5, 7, 'May');


/* ============================================================
   11. POKEMON ABILITIES
   Species-level abilities
   ============================================================ */

INSERT INTO PokemonAbility
    (species_id, ability_id, is_hidden)
VALUES

/* Bulbasaur */
    (1, 1, FALSE),
    (1, 2, TRUE),

/* Ivysaur */
    (2, 1, FALSE),
    (2, 2, TRUE),

/* Charmander */
    (3, 3, FALSE),
    (3, 4, TRUE),

/* Charmeleon */
    (4, 3, FALSE),
    (4, 4, TRUE),

/* Squirtle */
    (5, 5, FALSE),
    (5, 6, TRUE),

/* Pikachu */
    (6, 7, FALSE),
    (6, 8, TRUE),

/* Raichu */
    (7, 7, FALSE),
    (7, 8, TRUE),

/* Pidgey */
    (8, 9, FALSE),
    (8, 10, TRUE);


/* ============================================================
   12. POKEMON TYPES
   Species-level types
   ============================================================ */

INSERT INTO PokemonType
    (species_id, type_id)
VALUES

/* Bulbasaur */
    (1, 5),
    (1, 8),

/* Ivysaur */
    (2, 5),
    (2, 8),

/* Charmander */
    (3, 2),

/* Charmeleon */
    (4, 2),

/* Squirtle */
    (5, 3),

/* Pikachu */
    (6, 4),

/* Raichu */
    (7, 4),

/* Pidgey */
    (8, 1),
    (8, 10);


/* ============================================================
   13. POKEMON EGG GROUPS
   ============================================================ */

INSERT INTO PokemonEggGroup
    (species_id, egg_id)
VALUES

/* Bulbasaur */
    (1, 1),
    (1, 2),

/* Ivysaur */
    (2, 1),
    (2, 2),

/* Charmander */
    (3, 1),
    (3, 7),

/* Charmeleon */
    (4, 1),
    (4, 7),

/* Squirtle */
    (5, 1),
    (5, 6),

/* Pikachu */
    (6, 5),
    (6, 8),

/* Raichu */
    (7, 5),
    (7, 8),

/* Pidgey */
    (8, 4);


/* ============================================================
   14. INDIVIDUAL POKEMON
   ============================================================ */

INSERT INTO Pokemon
(
    pkmn_id,
    species_id,
    pkmn_name,
    gender,
    pkmn_lvl,
    pkmn_HP,
    pkmn_Atk,
    pkmn_Def,
    pkmn_SpA,
    pkmn_SpD,
    pkmn_Spe,
    nature,
    item_id,
    ability_id
)
VALUES

/* Individual Bulbasaur */
(
    1,
    1,
    'Bulby',
    1,
    10,
    32,
    18,
    19,
    22,
    21,
    16,
    'Modest',
    8,
    1
),

/* Second individual Bulbasaur */
(
    2,
    1,
    'Sprout',
    0,
    14,
    42,
    23,
    24,
    29,
    28,
    20,
    'Calm',
    NULL,
    1
),

/* Individual Ivysaur */
(
    3,
    2,
    'Ivy',
    1,
    22,
    61,
    34,
    35,
    44,
    43,
    32,
    'Timid',
    8,
    1
),

/* Individual Charmander */
(
    4,
    3,
    'Flame',
    1,
    12,
    35,
    22,
    19,
    25,
    21,
    30,
    'Jolly',
    6,
    3
),

/* Second Charmander */
(
    5,
    3,
    'Char',
    0,
    8,
    27,
    17,
    15,
    20,
    17,
    25,
    'Naive',
    NULL,
    3
),

/* Individual Charmeleon */
(
    6,
    4,
    'Blaze',
    1,
    30,
    78,
    48,
    42,
    60,
    48,
    64,
    'Hasty',
    6,
    3
),

/* Individual Squirtle */
(
    7,
    5,
    'Shelly',
    0,
    15,
    45,
    25,
    35,
    25,
    34,
    23,
    'Bold',
    7,
    5
),

/* Individual Pikachu */
(
    8,
    6,
    'Sparky',
    1,
    18,
    50,
    34,
    25,
    37,
    30,
    58,
    'Timid',
    9,
    7
),

/* Second Pikachu */
(
    9,
    6,
    'Volt',
    0,
    24,
    64,
    43,
    33,
    48,
    39,
    76,
    'Jolly',
    10,
    7
),

/* Raichu */
(
    10,
    7,
    'Thunder',
    1,
    35,
    94,
    70,
    48,
    72,
    65,
    102,
    'Hasty',
    10,
    7
),

/* Pidgey */
(
    11,
    8,
    'Sky',
    0,
    9,
    30,
    17,
    16,
    14,
    14,
    24,
    'Careful',
    NULL,
    9
);


/* ============================================================
   15. LEARNSET
   Moves that species CAN learn
   ============================================================ */

INSERT INTO Learnset
    (species_id, move_id, learn_method, learn_lvl)
VALUES

/* Bulbasaur */
    (1, 1, 'Level Up', '1'),
    (1, 2, 'Level Up', '3'),
    (1, 3, 'Level Up', '9'),
    (1, 6, 'TM', NULL),

/* Ivysaur */
    (2, 1, 'Level Up', '1'),
    (2, 2, 'Level Up', '1'),
    (2, 3, 'Level Up', '9'),
    (2, 5, 'TM', NULL),

/* Charmander */
    (3, 1, 'Level Up', '1'),
    (3, 4, 'Level Up', '1'),
    (3, 8, 'Level Up', '13'),
    (3, 5, 'TM', NULL),

/* Charmeleon */
    (4, 1, 'Level Up', '1'),
    (4, 4, 'Level Up', '1'),
    (4, 5, 'TM', NULL),
    (4, 8, 'Level Up', '13'),

/* Squirtle */
    (5, 1, 'Level Up', '1'),
    (5, 6, 'Level Up', '1'),
    (5, 8, 'Level Up', '7'),

/* Pikachu */
    (6, 1, 'Level Up', '1'),
    (6, 7, 'Level Up', '1'),
    (6, 8, 'Level Up', '5'),

/* Raichu */
    (7, 7, 'Level Up', '1'),
    (7, 8, 'Level Up', '1'),

/* Pidgey */
    (8, 1, 'Level Up', '1'),
    (8, 9, 'Level Up', '1'),
    (8, 8, 'Level Up', '5');


/* ============================================================
   16. POKEMON MOVES
   Moves currently known by individual Pokemon
   ============================================================ */

INSERT INTO PokemonMove
    (pkmn_id, move_id, current_pp)
VALUES

/* Bulby */
    (1, 1, 35),
    (1, 2, 25),
    (1, 3, 25),

/* Sprout */
    (2, 1, 35),
    (2, 2, 25),

/* Ivy */
    (3, 2, 25),
    (3, 3, 25),
    (3, 5, 15),

/* Flame */
    (4, 1, 35),
    (4, 4, 25),
    (4, 8, 30),

/* Char */
    (5, 1, 35),
    (5, 4, 25),

/* Blaze */
    (6, 4, 25),
    (6, 5, 15),
    (6, 8, 30),

/* Shelly */
    (7, 1, 35),
    (7, 6, 25),
    (7, 8, 30),

/* Sparky */
    (8, 1, 35),
    (8, 7, 30),
    (8, 8, 30),

/* Volt */
    (9, 7, 30),
    (9, 8, 30),

/* Thunder */
    (10, 7, 30),
    (10, 8, 30),

/* Sky */
    (11, 1, 35),
    (11, 9, 35),
    (11, 8, 30);


/* ============================================================
   17. EVOLUTIONS
   ============================================================ */

INSERT INTO Evolution
    (FromSpecies_id, ToSpecies_id, method, level)
VALUES
    (1, 2, 'Level Up', 16),
    (3, 4, 'Level Up', 16),
    (6, 7, 'Thunder Stone', NULL);


/* ============================================================
   18. ENCOUNTERS
   ============================================================ */

INSERT INTO Encounter
    (location_id, species_id, enc_low_lvl, enc_hi_lvl)
VALUES

/* Route 1 */
    (4, 8, 2, 5),
    (4, 6, 3, 5),

/* Viridian Forest */
    (2, 1, 3, 5),
    (2, 6, 3, 5),
    (2, 8, 3, 6),

/* Pallet Town */
    (1, 5, 5, 10),

/* Pewter City */
    (3, 3, 5, 10),

/* New Bark Town */
    (5, 6, 5, 8),

/* Violet City */
    (6, 8, 5, 10),

/* Littleroot Town */
    (7, 5, 5, 8),

/* Petalburg Woods */
    (8, 1, 5, 8);


/* ============================================================
   19. TRAINER POKEMON
   ============================================================ */

INSERT INTO TrainerPokemon
    (trainer_id, pkmn_id, slot)
VALUES

/* Professor Oak */
    (1, 1, 1),
    (1, 4, 2),
    (1, 7, 3),

/* Youngster Joey */
    (2, 11, 1),
    (2, 8, 2),

/* Brock */
    (3, 3, 1),
    (3, 7, 2),

/* Falkner */
    (4, 11, 1),

/* May */
    (5, 9, 1),
    (5, 5, 2);


/* ============================================================
   20. TRAINER ITEMS
   ============================================================ */

INSERT INTO TrainerItem
    (trainer_id, item_id, amount)
VALUES

/* Professor Oak */
    (1, 1, 10),
    (1, 3, 20),

/* Youngster Joey */
    (2, 1, 2),

/* Brock */
    (3, 2, 3),
    (3, 3, 5),

/* Falkner */
    (4, 1, 4),

/* May */
    (5, 1, 5),
    (5, 9, 2);


/* ============================================================
   21. TYPE EFFECTIVENESS
   ============================================================ */

INSERT INTO TypeEffectiveness
    (attack_type, defense_type, damage_mult)
VALUES

/* Normal */
    (1, 1, 1.00),
    (1, 13, 0.50),
    (1, 14, 0.00),

/* Fire */
    (2, 2, 0.50),
    (2, 3, 0.50),
    (2, 5, 2.00),
    (2, 6, 2.00),
    (2, 12, 2.00),
    (2, 13, 0.50),
    (2, 15, 0.50),

/* Water */
    (3, 2, 2.00),
    (3, 3, 0.50),
    (3, 5, 0.50),
    (3, 9, 2.00),
    (3, 13, 2.00),
    (3, 15, 0.50),

/* Electric */
    (4, 3, 2.00),
    (4, 4, 0.50),
    (4, 5, 0.50),
    (4, 9, 0.00),
    (4, 10, 2.00),
    (4, 15, 0.50),

/* Grass */
    (5, 2, 0.50),
    (5, 3, 2.00),
    (5, 5, 0.50),
    (5, 8, 0.50),
    (5, 9, 2.00),
    (5, 10, 0.50),
    (5, 12, 0.50),
    (5, 13, 2.00),
    (5, 15, 0.50),

/* Fighting */
    (7, 1, 2.00),
    (7, 6, 2.00),
    (7, 8, 0.50),
    (7, 10, 0.50),
    (7, 11, 0.50),
    (7, 14, 0.00),

/* Poison */
    (8, 5, 2.00),
    (8, 8, 0.50),
    (8, 9, 0.50),
    (8, 13, 0.50),
    (8, 14, 0.50),

/* Ground */
    (9, 2, 2.00),
    (9, 4, 2.00),
    (9, 5, 0.50),
    (9, 8, 2.00),
    (9, 10, 0.00),
    (9, 13, 2.00),

/* Flying */
    (10, 5, 2.00),
    (10, 4, 0.50),
    (10, 7, 2.00),
    (10, 12, 2.00),
    (10, 13, 0.50),

/* Psychic */
    (11, 7, 2.00),
    (11, 8, 2.00),
    (11, 11, 0.50),

/* Bug */
    (12, 5, 2.00),
    (12, 2, 0.50),
    (12, 7, 0.50),
    (12, 10, 0.50),
    (12, 13, 0.50),
    (12, 14, 0.50),

/* Rock */
    (13, 2, 2.00),
    (13, 6, 2.00),
    (13, 10, 2.00),
    (13, 7, 0.50),
    (13, 9, 0.50),

/* Ghost */
    (14, 1, 0.00),
    (14, 11, 2.00),
    (14, 14, 2.00),

/* Dragon */
    (15, 15, 2.00),
    (15, 2, 1.00),
    (15, 3, 1.00),
    (15, 5, 1.00);