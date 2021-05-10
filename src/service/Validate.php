<?php 



class Validate{


    private $vlaidate;
    private $em;

    public function __construct(ValidatorInterface $validator, Registry $registry){
        $this->validator = $validator;
        $this->registry = $registry;
    }

    public function validateRequest($data){
        $errors= $this->validator->validate($data);
        $errorsResponse = array();


        foreach($errors as $error){
            $errorsResponse[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }

        if(count($errors)){
            $response = array(
                'code'=> 1,
                'massage'=>'validation errors',
                'errors'=>$errorsResponse,
                'result' => null
            );

            return $response;
        }else{
            $response = [];
            return $response;
        }
    }
}