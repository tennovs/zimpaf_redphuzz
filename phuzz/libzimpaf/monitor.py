'''
Monitoring considers a potentially vulnerable function that is treated as web application library call
where it can be called from several modules/parts of the application code.
'''
import hashlib
import copy
from libzimpaf.constants import Key, CodeBase, VulnFuncStatus,Vulnerability

vuln_function_list = []

'''
register is only called once, from set api call trace status
algorithms:
1. check if include and require access .php or .html or .htm. If yes, very likely web application file,
   assume constant and do not fuzz. If it is malicious file, e.g. php shell/exploit, detection is done by
   code exec function fuzzing
'''

# f_func = fuzzed vulnerable function
# m_func = vulnerabl function in monitor
# c = candidate/input
def register_vuln_function(f_func, c): 
    if f_func:
        
        string_id = f_func[Key.FUNCTION_NAME] + f_func[Key.FILENAME] + str(f_func[Key.LINENO])
        id = hashlib.sha1(string_id.encode()).hexdigest()
        for m_func in vuln_function_list:
            if m_func[id] == id and c.path_hash not in m_func[Key.OWNER_CANDIDATES]:
                m_func[Key.OWNER_CANDIDATES].add(c.path_hash)
                m_func[Key.NUM_CANDIDATE_OWNS] += 1
                updated = update_last_sink(m_func, f_func)
                return updated
            elif m_func[id] == id and c.path_hash in m_func[Key.OWNER_CANDIDATES]:
                updated = update_last_sink(m_func, f_func)
                return updated
        new_func = copy_and_set_monitoring_attributes(f_func, c, id)
        vuln_function_list.append(new_func)
        return True
            

def create_monitored_function(f_func, c):
    string_id = f_func[Key.FUNCTION_NAME] + f_func[Key.FILENAME] + str(f_func[Key.LINENO])
    id = hashlib.sha1(string_id.encode()).hexdigest()
    m_func = {Key.FUNC_ID : id}
    
    if f_func[Key.VULN_FUNC_STATUS] == VulnFuncStatus.FUZZED:
        m_func[Key.CANDIDATES_FUZZ] = {c.path_hash}
    elif f_func[Key.VULN_FUNC_STATUS] == VulnFuncStatus.BUGGY:
        m_func[Key.CANDIDATES_BUGGY] = {c.path_hash}
    elif f_func[Key.VULN_FUNC_STATUS] == VulnFuncStatus.VULNERABLE:      
        m_func[Key.CANDIDATES_VULNERABLE]   ={c.path_hash}
    elif f_func[Key.VULN_FUNC_STATUS] == VulnFuncStatus.IN_SAFESEQ:
        m_func[Key.CANDIDATES_IN_SAFESEQ]   ={c.path_hash} 
    elif f_func[Key.VULN_FUNC_STATUS] == VulnFuncStatus.UNRESOLVED:
        m_func[Key.CANDIDATES_UNRESOLVED] = {c.path_hash}
    m_func[Key.LAST_SINK] = None
    update_last_sink(m_func, f_func)
           
    
            

    
def copy_and_set_monitoring_attributes(f_func, c, id):
    new_func = copy.deepcopy(f_func)
    new_func[Key.FUNC_ID] = id
    new_func[Key.OWNER_CANDIDATES] = set()
    new_func[Key.OWNER_CANDIDATES].add(c.path_hash)
    new_func[Key.NUM_CANDIDATE_OWNS] += 1
    new_func[Key.NUM_FUZZED] = 1 #it is not actually fuzzed, just identified as new path uncovered
    new_func[Key.LAST_SINK] = None
    updated = update_last_sink(new_func, f_func)
    new_func[Key.NUM_UNRESOLVED_END] = 0
    return updated

              

def update_last_sink(m_func, f_func):
    updated = False
    if Key.COMMAND in f_func:
        sink_key = Key.COMMAND
    elif Key.PATH in f_func:
        sink_key = Key.PATH
    elif Key.QUERY in f_func:
        sink_key = Key.QUERY
    elif Key.SERIALIZED_STRING in f_func:
        sink_key = Key.SERIALIZED_STRING
    elif Key.XML_PAYLOAD in f_func:
        sink_key = Key.XML_PAYLOAD
    
    if m_func[Key.LAST_SINK] == None:           #new vuln function enters monitor
        m_func[Key.PREVIOUS_SINK] = f_func[sink_key]
        m_func[Key.LAST_SINK] = f_func[sink_key]
        m_func[Key.NUM_LAST_SINK_CONSTANT] = 0
        m_func[Key.NUM_LAST_SINK_CHANGES] = 0
        updated = True
    else: 
        if m_func[Key.LAST_SINK] != f_func[sink_key]:
            m_func[Key.PREVIOUS_SINK] = m_func[Key.LAST_SINK] #m_func_sink_key act as previous sink
            m_func[Key.LAST_SINK] = f_func[sink_key]
            m_func[Key.NUM_LAST_SINK_CHANGES] += 1
            updated = True
        else:
            m_func[Key.NUM_LAST_SINK_CONSTANT] += 1
            updated = False   
    return updated

def update_monitored_func(vuln_func, c):
    pass





# def add_to_monitor(vuln_function):
#     pass

