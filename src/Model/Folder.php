<?php

namespace PwBox\Model;

    class Folder
    {
        private $id_folder;
        private $nom_folder;
        private $complete_path;
        private $folder_mare;

        /**
         * Folder constructor.
         * @param $id_folder
         * @param $nom_folder
         * @param $complete_path
         * @param $folder_mare
         */
        public function __construct($id_folder, $nom_folder, $complete_path, $folder_mare)
        {
            $this->id_folder = $id_folder;
            $this->nom_folder = $nom_folder;
            $this->complete_path = $complete_path;
            $this->folder_mare = $folder_mare;
        }

        /**
         * @return mixed
         */
        public function getIdFolder()
        {
            return $this->id_folder;
        }

        /**
         * @return mixed
         */
        public function getNomFolder()
        {
            return $this->nom_folder;
        }

        /**
         * @return mixed
         */
        public function getCompletePath()
        {
            return $this->complete_path;
        }

        /**
         * @return mixed
         */
        public function getFolderMare()
        {
            return $this->folder_mare;
        }

    }

