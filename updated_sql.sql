DROP DATABASE IF EXISTS Pokemon_DB;

CREATE DATABASE Pokemon_DB;

USE Pokemon_DB;

CREATE TABLE Region (
    region_id INT AUTO_INCREMENT,
    region_name VARCHAR(50) NOT NULL,
    region_gen INT,
    PRIMARY KEY (region_id)
);

CREATE TABLE Leveling_Rate (
    LR_id INT AUTO_INCREMENT,
    rate_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (LR_id)
);

CREATE TABLE Egg_Group (
    egg_id INT AUTO_INCREMENT,
    egg_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (egg_id)
);

CREATE TABLE Ability (
    ability_id INT AUTO_INCREMENT,
    ability_name VARCHAR(100) NOT NULL,
    ability_desc TEXT,
    PRIMARY KEY (ability_id)
);

CREATE TABLE `Type` (
    type_id INT AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (type_id)
);

CREATE TABLE Item (
    item_id INT AUTO_INCREMENT,
    item_name VARCHAR(100) NOT NULL,
    item_desc TEXT,
    PRIMARY KEY (item_id)
);

CREATE TABLE Pokemon_Species (
    species_id INT AUTO_INCREMENT,
    dex_num INT,
    species_name VARCHAR(100) NOT NULL,
    base_species VARCHAR(100),
    forme VARCHAR(100),
    gender INT,
    gender_ratio DECIMAL(5,2),
    not_fully_evolved BOOLEAN,
    weight_kg DECIMAL(6,2),
    LR_id INT,
    base_HP INT,
    base_Atk INT,
    base_Def INT,
    base_SpA INT,
    base_SpD INT,
    base_Spe INT,
    pkmn_tag VARCHAR(100),
    pkmn_color VARCHAR(50),
    PRIMARY KEY (species_id)
);

CREATE TABLE Location (
    location_id INT AUTO_INCREMENT,
    region_id INT,
    location_name VARCHAR(100) NOT NULL,
    PRIMARY KEY (location_id)
);

CREATE TABLE `Move` (
    move_id INT AUTO_INCREMENT,
    type_id INT,
    move_name VARCHAR(100) NOT NULL,
    move_bp INT,
    move_category VARCHAR(50),
    move_pp INT,
    move_accuracy INT,
    move_effect TEXT,
    move_desc TEXT,
    PRIMARY KEY (move_id)
);

CREATE TABLE Trainer (
    trainer_id INT AUTO_INCREMENT,
    location_id INT,
    trainer_name VARCHAR(100) NOT NULL,
    PRIMARY KEY (trainer_id)
);

CREATE TABLE PokemonAbility (
    species_id INT NOT NULL,
    ability_id INT NOT NULL,
    is_hidden BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (species_id, ability_id)
);

CREATE TABLE PokemonType (
    species_id INT NOT NULL,
    type_id INT NOT NULL,
    PRIMARY KEY (species_id, type_id)
);

CREATE TABLE PokemonEggGroup (
    species_id INT NOT NULL,
    egg_id INT NOT NULL,
    PRIMARY KEY (species_id, egg_id)
);

CREATE TABLE Pokemon (
    pkmn_id INT AUTO_INCREMENT,
    species_id INT NOT NULL,
    pkmn_name VARCHAR(100),
    gender INT,
    pkmn_lvl INT,
    pkmn_HP INT,
    pkmn_Atk INT,
    pkmn_Def INT,
    pkmn_SpA INT,
    pkmn_SpD INT,
    pkmn_Spe INT,
    nature VARCHAR(50),
    item_id INT NULL,
    ability_id INT NULL,
    PRIMARY KEY (pkmn_id)
);

CREATE TABLE Learnset (
    species_id INT NOT NULL,
    move_id INT NOT NULL,
    learn_method VARCHAR(100),
    learn_lvl VARCHAR(50),
    PRIMARY KEY (species_id, move_id)
);

CREATE TABLE PokemonMove (
    pkmn_id INT NOT NULL,
    move_id INT NOT NULL,
    current_pp INT,
    PRIMARY KEY (pkmn_id, move_id)
);

CREATE TABLE Evolution (
    FromSpecies_id INT NOT NULL,
    ToSpecies_id INT NOT NULL,
    method VARCHAR(100),
    level INT,
    PRIMARY KEY (FromSpecies_id, ToSpecies_id)
);

CREATE TABLE Encounter (
    location_id INT NOT NULL,
    species_id INT NOT NULL,
    enc_low_lvl INT,
    enc_hi_lvl INT,
    PRIMARY KEY (location_id, species_id)
);

CREATE TABLE TrainerPokemon (
    trainer_id INT NOT NULL,
    pkmn_id INT NOT NULL,
    slot INT NOT NULL,
    PRIMARY KEY (trainer_id, pkmn_id)
);

CREATE TABLE TrainerItem (
    trainer_id INT NOT NULL,
    item_id INT NOT NULL,
    amount INT NOT NULL DEFAULT 0,
    PRIMARY KEY (trainer_id, item_id)
);

CREATE TABLE TypeEffectiveness (
    attack_type INT NOT NULL,
    defense_type INT NOT NULL,
    damage_mult DECIMAL(4,2) NOT NULL,
    PRIMARY KEY (attack_type, defense_type)
);

ALTER TABLE Pokemon_Species
ADD CONSTRAINT fk_species_leveling_rate
    FOREIGN KEY (LR_id)
    REFERENCES Leveling_Rate(LR_id);

ALTER TABLE Location
ADD CONSTRAINT fk_location_region
    FOREIGN KEY (region_id)
    REFERENCES Region(region_id);

ALTER TABLE `Move`
ADD CONSTRAINT fk_move_type
    FOREIGN KEY (type_id)
    REFERENCES `Type`(type_id);

ALTER TABLE Trainer
ADD CONSTRAINT fk_trainer_location
    FOREIGN KEY (location_id)
    REFERENCES Location(location_id);

ALTER TABLE PokemonAbility
ADD CONSTRAINT fk_pokemonability_species
    FOREIGN KEY (species_id)
    REFERENCES Pokemon_Species(species_id);

ALTER TABLE PokemonAbility
ADD CONSTRAINT fk_pokemonability_ability
    FOREIGN KEY (ability_id)
    REFERENCES Ability(ability_id);

ALTER TABLE PokemonType
ADD CONSTRAINT fk_pokemontype_species
    FOREIGN KEY (species_id)
    REFERENCES Pokemon_Species(species_id);

ALTER TABLE PokemonType
ADD CONSTRAINT fk_pokemontype_type
    FOREIGN KEY (type_id)
    REFERENCES `Type`(type_id);

ALTER TABLE PokemonEggGroup
ADD CONSTRAINT fk_pokemonegg_species
    FOREIGN KEY (species_id)
    REFERENCES Pokemon_Species(species_id);

ALTER TABLE PokemonEggGroup
ADD CONSTRAINT fk_pokemonegg_group
    FOREIGN KEY (egg_id)
    REFERENCES Egg_Group(egg_id);

ALTER TABLE Pokemon
ADD CONSTRAINT fk_pokemon_species
    FOREIGN KEY (species_id)
    REFERENCES Pokemon_Species(species_id);

ALTER TABLE Pokemon
ADD CONSTRAINT fk_pokemon_item
    FOREIGN KEY (item_id)
    REFERENCES Item(item_id);

ALTER TABLE Pokemon
ADD CONSTRAINT fk_pokemon_species_ability
    FOREIGN KEY (species_id, ability_id)
    REFERENCES PokemonAbility(species_id, ability_id);

ALTER TABLE Learnset
ADD CONSTRAINT fk_learnset_species
    FOREIGN KEY (species_id)
    REFERENCES Pokemon_Species(species_id);

ALTER TABLE Learnset
ADD CONSTRAINT fk_learnset_move
    FOREIGN KEY (move_id)
    REFERENCES `Move`(move_id);

ALTER TABLE PokemonMove
ADD CONSTRAINT fk_pokemonmove_pokemon
    FOREIGN KEY (pkmn_id)
    REFERENCES Pokemon(pkmn_id);

ALTER TABLE PokemonMove
ADD CONSTRAINT fk_pokemonmove_move
    FOREIGN KEY (move_id)
    REFERENCES `Move`(move_id);

ALTER TABLE Evolution
ADD CONSTRAINT fk_evolution_from_species
    FOREIGN KEY (FromSpecies_id)
    REFERENCES Pokemon_Species(species_id);

ALTER TABLE Evolution
ADD CONSTRAINT fk_evolution_to_species
    FOREIGN KEY (ToSpecies_id)
    REFERENCES Pokemon_Species(species_id);

ALTER TABLE Encounter
ADD CONSTRAINT fk_encounter_location
    FOREIGN KEY (location_id)
    REFERENCES Location(location_id);

ALTER TABLE Encounter
ADD CONSTRAINT fk_encounter_species
    FOREIGN KEY (species_id)
    REFERENCES Pokemon_Species(species_id);

ALTER TABLE TrainerPokemon
ADD CONSTRAINT fk_trainerpokemon_trainer
    FOREIGN KEY (trainer_id)
    REFERENCES Trainer(trainer_id);

ALTER TABLE TrainerPokemon
ADD CONSTRAINT fk_trainerpokemon_pokemon
    FOREIGN KEY (pkmn_id)
    REFERENCES Pokemon(pkmn_id);

ALTER TABLE TrainerItem
ADD CONSTRAINT fk_traineritem_trainer
    FOREIGN KEY (trainer_id)
    REFERENCES Trainer(trainer_id);

ALTER TABLE TrainerItem
ADD CONSTRAINT fk_traineritem_item
    FOREIGN KEY (item_id)
    REFERENCES Item(item_id);

ALTER TABLE TypeEffectiveness
ADD CONSTRAINT fk_typeeffect_attack
    FOREIGN KEY (attack_type)
    REFERENCES `Type`(type_id);

ALTER TABLE TypeEffectiveness
ADD CONSTRAINT fk_typeeffect_defense
    FOREIGN KEY (defense_type)
    REFERENCES `Type`(type_id);