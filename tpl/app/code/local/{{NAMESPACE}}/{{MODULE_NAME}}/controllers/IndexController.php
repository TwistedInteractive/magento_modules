<?php
/**
 * (c) 2012
 * Author: Giel Berkers
 * Date: 22-3-12
 * Time: 14:56
 */

class {{NAMESPACE}}_{{MODULE_NAME}}_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * New action forwards to edit action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('{{NAME_LOWERCASE}}/main');
        if ($id) {
            $model->load((int)$id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('{{NAME_LOWERCASE}}')->__('Item does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('{{NAME_LOWERCASE}}_data', $model);

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * Save action
     * @return mixed
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('{{NAME_LOWERCASE}}/main');
            $id = $this->getRequest()->getParam('id');
	        $duplicate = $this->getRequest()->getParam('duplicate') == '1';

            if ($id && !$duplicate) {
                $model->load($id);
            }

            // File uploads:
			{{FILE_UPLOADS}}

	        // Set the data:
            $model->setData($data);

            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id && !$duplicate) {
                    $model->setId($id);
                }
                $model->save();

                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('{{NAME_LOWERCASE}}')->__('Error saving item'));
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('{{NAME_LOWERCASE}}')->__('Item was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // The following line decides if it is a "save" or "save and continue"
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($model && $model->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            }

            return;
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('{{NAME_LOWERCASE}}')->__('No data found to save'));
        $this->_redirect('*/*/');
    }

	private function handleUpload($name, $duplicate, $data)
	{

		if (isset($_FILES[$name]['name']) && (file_exists($_FILES[$name]['tmp_name']))) {
			try {
				$oldFile = (isset($data[$name]) && is_array($data[$name])) ? $data[$name]['value'] : false;

				$path = Mage::getBaseDir('media') . DS;
				$fullpath = $this->getIncreasedFile($path.'/'.$_FILES[$name]['name']);
				$info = pathinfo($fullpath);
				move_uploaded_file($_FILES[$name]['tmp_name'], $fullpath);
				$data[$name] = $info['basename'];

				// Delete old file:
				if($oldFile != false)
				{
					if(file_exists(Mage::getBaseDir('media').DS.$oldFile))
					{
						unlink(Mage::getBaseDir('media').DS.$oldFile);
					}
				}
			} catch (Exception $e) {

			}
		} else {
			if(isset($data[$name]) && is_array($data[$name]))
			{
				if($duplicate == true)
				{
					// Make a copy of the image:
					$hash = substr(md5(time()), 0, 8);
					copy(Mage::getBaseDir('media').DS.$data[$name]['value'], Mage::getBaseDir('media').DS.$hash.'-'.$data[$name]['value']);
					$data[$name]['value'] = $hash.'-'.$data[$name]['value'];
				}
				if(isset($data[$name]['delete']) && $data[$name]['delete'] == 1)
				{
					// delete the file:
					if(file_exists(Mage::getBaseDir('media').DS.$data[$name]['value']))
					{
						unlink(Mage::getBaseDir('media').DS.$data[$name]['value']);
					}
					$data[$name] = null;
				} else {
					$data[$name] = $data[$name]['value'];
				}
			}
		}
		return $data;
	}

	private function getIncreasedFile($path, $next = 2)
	{
		if(file_exists($path))
		{
			$info = pathinfo($path);
			$newpath = $info['dirname'].'/'.$info['filename'].'-'.$next.'.'.$info['extension'];
			if(file_exists($newpath))
			{
				return $this->getIncreasedFile($path, $next + 1);
			} else {
				return $newpath;
			}
		} else {
			return $path;
		}
	}

    /**
     * Delete action
     * @return mixed
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
	        $this->__delete($id);
        } else {
	        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('{{NAME_LOWERCASE}}')->__('Unable to find the item to delete.'));
	        $this->_redirect('*/*/');
        }
    }

	private function __delete($id)
	{
		try {
			$model = Mage::getModel('{{NAME_LOWERCASE}}/main');
			$model->setId($id);
			$model->load($id);
			$data = $model->getData();
			if(!empty($data['image']) && file_exists(Mage::getBaseDir('media').DS.$data['image'])) {
				unlink(Mage::getBaseDir('media').DS.$data['image']);
			}
			if(!empty($data['thumbnail']) && file_exists(Mage::getBaseDir('media').DS.$data['thumbnail'])) {
				unlink(Mage::getBaseDir('media').DS.$data['thumbnail']);
			}
			$model->delete();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('{{NAME_LOWERCASE}}')->__('The item has been deleted.'));
			$this->_redirect('*/*/');
			return;
		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			return;
		}
	}

	/**
	 * Mass Delete
	 */
	public function massDeleteAction()
	{
		foreach($this->getRequest()->getParam('{{NAME_LOWERCASE}}') as $id)
		{
			$this->__delete($id);
		}
		$this->_redirect('*/*/index');
	}

	/**
	 * Mass duplicate
	 */
	public function massDuplicateAction()
	{
		foreach($this->getRequest()->getParam('{{NAME_LOWERCASE}}') as $id)
		{
			// Load the original image:
			$data = Mage::getModel('{{NAME_LOWERCASE}}/main')->load($id)->getData();
			unset($data['id']);

			// Duplicate the images:
			if(!empty($data['image']))
			{
				$hash = substr(md5(time()), 0, 8);
				copy(Mage::getBaseDir('media') . DS . $data['image'], Mage::getBaseDir('media') . DS . $hash . '-' . $data['image']);
				$data['image'] = $hash . '-' . $data['image'];
			}
			if(!empty($data['thumbnail']))
			{
				$hash = substr(md5(time()), 0, 8);
				copy(Mage::getBaseDir('media') . DS . $data['thumbnail'], Mage::getBaseDir('media') . DS . $hash . '-' . $data['thumbnail']);
				$data['thumbnail'] = $hash . '-' . $data['thumbnail'];
			}

			// Create a new model:
			$model = Mage::getModel('{{NAME_LOWERCASE}}/main');
			$model->setData($data);
			$model->save();
		}
		$this->_redirect('*/*/index');
	}

}
